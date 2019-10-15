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
        ConfigReader $configReader )
    {
        $this->pluginName = $pluginName;
        $this->pluginDirectory = $pluginDirectory;

        $this->config = $configReader->getByPluginName( $pluginName );

        $this->db = Shopware()->Container()->get('dbal_connection');
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Controllers_Backend_OrderState_Notify' => 'changeOrderState',     // Notify
            'Shopware_Modules_Order_SaveOrder_ProcessDetails' => 'logOrder' ,           // Notify
            'Shopware_Modules_Basket_AddArticle_Added' => 'saveSageAttr' ,              // Notify
            'Shopware_Modules_Basket_AddArticle_FilterSql' => 'readSageAttr'
        ];
    }

    public function changeOrderState(\Enlight_Event_EventArgs $args)
    {
        // 'subject', 'id', 'status', 'mailname'
        $subject = $args->getSubject();
        $status = $args->getStatus();
        $mail = $args->getMailname();
        $id = $args->getId();

        $orderNumberSQL = $this->db->fetchAll( 'SELECT * FROM s_order WHERE id = ?', [ $id ] );
        $number = print_r( $orderNumberSQL[0]['ordernumber'], true );

        if( $status == 2 ) {

            $this->db->insert(
                'cp_order_status',
                [
                    'orderid' => $id ,
                    'orderNumber'=> $number ,
                    'status' => '0' ,
                    'comment' => 'status: ' . $status . ' - mail: ' . $mail . ' - id: ' . $id . ' - number: ' . $number
                ]
            );

        }
    }

    public function logOrder(\Enlight_Event_EventArgs $args)
    {
        $orderDetails = $args->getDetails();
        $order = $args->getSubject();
        $orderNumber = $order->sOrderNumber;
        
        $this->db->insert(
            'cp_order_status',
            [
                'orderid' => $orderNumber,
                'status' => '0',
            ]
        );
    }

    public function saveSageAttr(\Enlight_Event_EventArgs $args)
    {
        $attrId = $args->getId();

        $this->db->executeUpdate(
            "UPDATE s_order_basket_attributes
            SET cp_sage_stock_code = '" . $this->sage200code . "' ,
            cp_sage_purchaseunit = '" . ( (int)$this->purchaseunit ) . "'
            WHERE id = ".$attrId , []
        );
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
