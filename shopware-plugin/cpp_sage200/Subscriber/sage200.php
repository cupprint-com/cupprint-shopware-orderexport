<?php
namespace cpp_sage200\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\ConfigReader;
use cppDesignOption\Utitlities\cupprint\cpApiUtilities;

class sage200 implements SubscriberInterface
{
    public $debug = false;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @var string
     */
    private $pluginName;

    /**
     * @var string
     */
    private $pluginDirectory;

    private $db = NULL;

    private $itemsPerUnit = "";
    private $purchaseunit = "";
    private $sage200code = "";

    /**
     * @param $pluginDirectory
     * @param \Enlight_Template_Manager $templateManager
     */
    public function __construct(
        $pluginName ,
        $pluginDirectory ,
        ConfigReader $configReader ,
        ModelManager $modelManager )
    {
        $this->pluginName = $pluginName;
        $this->pluginDirectory = $pluginDirectory;

        $this->modelManager = $modelManager;

        $this->config = $configReader->getByPluginName( $pluginName );

        $this->db = Shopware()->Container()->get('dbal_connection');
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Controllers_Backend_sKUZOOffer::saveOrderAction::after' => 'createOrdersKUZOOfferAction_after',
            
            'Shopware_Controllers_Backend_Order::saveAction::after' => 'changeOrder',     // Notify
            'Shopware_Controllers_Backend_OrderState_Notify' => 'changeOrderState',     // Notify

            'Shopware_Modules_Basket_AddArticle_Added' => 'saveSageAttr' ,              // Notify
            'Shopware_Modules_Basket_AddArticle_FilterSql' => 'readSageAttr'
        ];
    }


    // Offer - Order Action (Normal)
    public function createOrdersKUZOOfferAction_after(\Enlight_Event_EventArgs $args): void
    {

        $subject  = $args->getSubject();
        $view     = $subject->View(); // ->getAssign('success');
        # $return = $args->getReturn();
        $offerId  = (int)$_REQUEST['offerId'];

        if( $view->getAssign('success') && $offerId ) {
            /**
             * $offerId $offer
             * $orderId $order
             * $customerId
             */

            // Offer-Model
            $offer = $this->modelManager->find( \Shopware\CustomModels\Offer\Offer::class, $offerId ) ;

            // OrderID
            $sRes = $this->db->fetchAll('SELECT id, orderID, paymentID FROM s_offer WHERE id = ?', [$offerId]);
            $orderId = $sRes[0]['orderID']; // $orderId = $offer->getOrderID();

            $this->copySageCode( $orderId );

            $this->exportOrder( $orderId );
                    
        }
    }

    // Offer - Order Action (Normal)
    public function changeOrder(\Enlight_Hook_HookArgs $arguments)
    {
        $request = $arguments->getSubject()->Request();
        
        $this->exportOrder( $request->getParam('id') );
    }

    public function changeOrderState(\Enlight_Event_EventArgs $args)
    {


        // 'subject', 'id', 'status', 'mailname'
        $subject = $args->getSubject();
        $status = $args->getStatus();
        $mail = $args->getMailname();
        $id = $args->getId();



    }


    public function exportOrder($orderid){
        $orderDetails = $this->getDetailsFromOrderid($orderid);

        $ExportStatus = $this->getExportStatus( $orderDetails );

        if( $ExportStatus ) {
            $this->insert(
                $orderDetails['id'] ,
                $orderDetails['ordernumber'] ,

                $ExportStatus['status'] ,
                $ExportStatus['comment']
            );
        }
    }
    
    public function getDetailsFromOrderid($orderid){
        $orderNumberSQL = $this->db->fetchAll( '
            SELECT
                s_order.id,
                s_order.ordernumber,
                s_order.status,
                s_order.paymentID,
                s_order_billingaddress.countryID,
                s_core_countries.countryiso
            FROM
                s_order_billingaddress
            LEFT JOIN
                s_order
            ON
                s_order.id = s_order_billingaddress.orderID  
            LEFT JOIN
                s_core_countries
            ON
                s_core_countries.id = s_order_billingaddress.countryID
            WHERE
                s_order.id = ?
            ORDER BY
                s_order_billingaddress.countryID DESC
            ',
            [ $orderid ] );
        
        return $orderNumberSQL[0]; // [ (order)id, ordernumber, status, paymentID, countryID, countryiso ]
    }

    public function getExportStatus($orderDetails){
        $export = true;

        // condition

        if( $orderDetails['status'] == 2 || $orderDetails['paymentID'] == 5 ) {

            $status = 0;
            $comment = [];

            if( $paymentID == 5 ) {
                $comment[] = '"Payment":"Prepayment"';
            }

            $comment[] = '"countryiso":"' . $orderDetails['countryiso'].'"';
            if( in_array( $orderDetails['countryiso'], array('AT','DE','CH') ) ) {
                $status = 3;
            }

            return [ 
                'status' => $status ,
                'comment' => '{'.str_replace('"','\"',implode(',',$comment)).'}'
            ];
        }

        return false;
    }

    public function insert( $orderid = 0, $orderNumber, $status = 0, $comment = '' ) {

        $sRes = $this->db->fetchAll('SELECT orderid, orderNumber FROM cp_order_status WHERE orderNumber = ?', [$orderNumber]);
        
        if( !count( $sRes ) ) {
            $sql = '
                INSERT INTO cp_order_status (
                    orderid, orderNumber, status, comment
                )
                VALUES ( '.$orderid.', "'.$orderNumber.'", '.$status.', "'.$comment.'" )
                ';

            // $params = [ $orderid, $orderNumber, $status, $comment ];

            $this->db->query($sql );
        }
    }

    public function copySageCode( $orderid )
    {
        if( $orderid )  {
            $sql = 'UPDATE s_order_details_attributes
                    LEFT JOIN s_order_details
                    ON s_order_details.id = s_order_details_attributes.detailID
                    SET cp_sage_stock_code = (
                        SELECT s_articles_attributes.cp_sage_stock_code
                            FROM s_articles_attributes

                            LEFT JOIN s_articles_details
                                    ON s_articles_attributes.articledetailsID = s_articles_details.id
                            WHERE
                                s_articles_details.articleID = s_order_details.articleID
                            LIMIT 1
                    )
                    WHERE s_order_details.orderID = "' . $orderid . '"';
            $this->db->executeUpdate($sql);

            $sql = 'UPDATE s_order_details_attributes
                    LEFT JOIN s_order_details
                    ON s_order_details.id = s_order_details_attributes.detailID
                    SET cp_sage_purchaseunit = (
                        SELECT  s_articles_details.purchaseunit
                            FROM s_articles_details
                            WHERE
                                s_articles_details.articleID = s_order_details.articleID
                            LIMIT 1
                    )
                    WHERE s_order_details.orderID = "' . $orderid . '"';

            $this->db->executeUpdate($sql);
        }
    }

    public function logOrder(\Enlight_Event_EventArgs $args)
    {
        $orderDetails = $args->getDetails();
        $order = $args->getSubject();
        $orderNumber = $order->sOrderNumber;
        
    }

    public function saveSageAttr(\Enlight_Event_EventArgs $args)
    {
        $attrId = $args->getId();

    }

    public function readSageAttr(\Enlight_Event_EventArgs $args)
    {
        $sql = $args->getSql;

        $article = $args->getArticle();
        $articledetailsID = $article['articledetailsID'];

        $attrSQL = $this->db->fetchAll('SELECT * FROM s_articles_attributes WHERE articledetailsID = ?', [$articledetailsID]);
        $this->sage200code = $attrSQL[0]['cp_sage_stock_code'];

        $attrSQL = $this->db->fetchAll('SELECT * FROM s_articles_details WHERE id = ?', [$articledetailsID]);
        $this->purchaseunit = $attrSQL[0]['purchaseunit'];

        return $sql;
    }
}
