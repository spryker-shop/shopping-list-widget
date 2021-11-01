<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetConfig getConfig()
 */
class ShoppingListFromCartForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NEW_SHOPPING_LIST_NAME_INPUT = 'newShoppingListName';

    /**
     * @var string
     */
    public const OPTION_SHOPPING_LISTS = 'OPTION_SHOPPING_LISTS';

    /**
     * @var string
     */
    protected const FIELD_ID_QUOTE = 'idQuote';

    /**
     * @var string
     */
    protected const FIELD_ID_SHOPPING_LIST = 'idShoppingList';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addQuoteTransferField($builder);
        $this->addShoppingListField($builder, $options);
        $this->addShoppingListNameField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_SHOPPING_LISTS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addQuoteTransferField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_ID_QUOTE, HiddenType::class);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addShoppingListField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_ID_SHOPPING_LIST, ChoiceType::class, [
            'choices' => $options[static::OPTION_SHOPPING_LISTS],
            'expanded' => false,
            'required' => true,
            'label' => 'customer.account.shopping_list.create_from_cart.choose_shopping_list',
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addShoppingListNameField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_NEW_SHOPPING_LIST_NAME_INPUT, TextType::class, [
            'label' => 'customer.account.shopping_list.create_from_cart.name',
            'mapped' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'cart.add-to-shopping-list.form.placeholder',
            ],
            'constraints' => [
                new Callback([
                    'callback' => $this->nameValidateCallback($builder),
                ]),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Closure
     */
    protected function nameValidateCallback(FormBuilderInterface $builder): callable
    {
        return function ($object, ExecutionContextInterface $context) use ($builder) {
            $data = $builder->getData();
            if (!$object && !$data[static::FIELD_ID_SHOPPING_LIST]) {
                $context->buildViolation('cart.add-to-shopping-list.form.error.empty_name')
                    ->addViolation();
            }
        };
    }
}
