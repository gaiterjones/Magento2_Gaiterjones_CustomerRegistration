<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gaiterjones\CustomerRegistration\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $attributesInfo = [
            'gj_custom_boolean_attribute' => [
                "type"          => "int",
                "backend"       => "",
                "label"         => "Customer Custom Boolean Attribute",
                "input"         => "boolean",
                "source"        => Boolean::class,
                "visible"       => true,
                "required"      => false,
                'user_defined'  => true,
                'system'        => false,
                'global'        => true,
                'default'       => 0,
                "default"       => 0,
                'visible_on_front' => true,
                "unique"        => false,
                "note"          => "",
                'sort_order'    => 900,
                'position'      => 900
            ],
                'gj_custom_text_attribute' => [
                "type"          => "varchar",
                "backend"       => "",
                "label"         => "Customer Custom Text Attribute",
                "input"         => "text",
                "source"        => "",
                "visible"       => true,
                "required"      => false,
                'user_defined'  => true,
                'system'        => false,
                'global'        => true,
                'default'       => 0,
                "default"       => 0,
                'visible_on_front' => true,
                "unique"        => false,
                "note"          => "",
                'sort_order'    => 901,
                'position'      => 901
            ]
        ];

        $used_in_forms[]="adminhtml_customer";
        $used_in_forms[]="checkout_register";
        $used_in_forms[]="customer_account_create";
        $used_in_forms[]="customer_account_edit";
        $used_in_forms[]="adminhtml_checkout";

        foreach ($attributesInfo as $attributeCode => $attributeParams) {
            $customerSetup->addAttribute(Customer::ENTITY, $attributeCode, $attributeParams);
            $attribute = $customerSetup->getEavConfig()
                                        ->getAttribute(Customer::ENTITY, $attributeCode)
                                        ->addData(
                                            [
                                                'attribute_set_id' => $attributeSetId,
                                                'attribute_group_id' => $attributeGroupId,
                                                'used_in_forms'=> $used_in_forms
                                            ]
                                        );
            $attribute->save();
        }
        $setup->endSetup();
    }
}
