<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Sales
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Assign order status to order state form
 */
namespace Magento\Sales\Block\Adminhtml\Order\Status\Assign;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Sales\Model\Resource\Order\Status\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Core\Model\Registry $registry
     * @param \Magento\Data\FormFactory $formFactory
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Sales\Model\Resource\Order\Status\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Core\Model\Registry $registry,
        \Magento\Data\FormFactory $formFactory,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Sales\Model\Resource\Order\Status\CollectionFactory $collectionFactory,
        array $data = array()
    ) {
        $this->_orderConfig = $orderConfig;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('order_status_state');
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Data\Form $form */
        $form = $this->_formFactory->create(array(
            'data' => array(
                'id'        => 'edit_form',
                'method'    => 'post',
            ))
        );

        $fieldset   = $form->addFieldset('base_fieldset', array(
            'legend'    => __('Assignment Information')
        ));

        $statuses = $this->_collectionFactory->create()->toOptionArray();
        array_unshift($statuses, array('value' => '', 'label' => ''));

        $states = $this->_orderConfig->getStates();
        $states = array_merge(array('' => ''), $states);

        $fieldset->addField('status', 'select',
            array(
                'name'      => 'status',
                'label'     => __('Order Status'),
                'class'     => 'required-entry',
                'values'    => $statuses,
                'required'  => true,
            )
        );

        $fieldset->addField('state', 'select',
            array(
                'name'      => 'state',
                'label'     => __('Order State'),
                'class'     => 'required-entry',
                'values'    => $states,
                'required'  => true,
            )
        );

        $fieldset->addField('is_default', 'checkbox',
            array(
                'name'      => 'is_default',
                'label'     => __('Use Order Status As Default'),
                'value'     => 1,
            )
        );


        $form->setAction($this->getUrl('sales/order_status/assignPost'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
