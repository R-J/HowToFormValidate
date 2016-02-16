<?php defined('APPLICATION') or die;

$PluginInfo['HowToFormValidate'] = array(
    'Name' => 'HowTo: Form & Validation',
    'Description' => 'Custom form and validation example.',
    'Version' => '0.3',
    'RequiredApplications' => array('Vanilla' => '>= 2.2'),
    'RequiredTheme' => false,
    'MobileFriendly' => true,
    'HasLocale' => false,
    'Author' => 'Robin Jurinka',
    'AuthorUrl' => 'http://vanillaforums.org/profile/44046/R_J',
    'License' => 'MIT'
);



/**
 * Custom form and input validation example.
 *
 * Shows common input fields and how to add a validation to them.
 *
 * @package HowToFormValidate
 * @author Robin Jurinka
 * @license MIT
 */
class HowToFormValidatePlugin extends Gdn_Plugin {
    /**
     * Setup is run whenever plugin is enabled.
     *
     * @return void.
     * @package HowToFormValidate
     * @since 0.1
     */
    public function setup() {

    }


    /**
     * OnDisable is run whenever plugin is disabled.
     *
     * @return void.
     * @package HowToFormValidate
     * @since 0.1
     */
    public function onDisable() {

    }


    /**
     * Create a link to our page in the menu.
     *
     * @param object $sender Garden Controller.
     * @return void.
     * @package HowToFormValidate
     * @since 0.1
     */
    public function base_render_before($sender) {
        // We only need to add the menu entry if a) the controller has a menu
        // and b) we are not in the admin area.
        if ($sender->Menu && $sender->MasterView != 'admin') {
            $sender->Menu->addLink('', t('Form & Validation'), 'vanilla/HowToFormValidate');
        }
    }

    /**
     * Create a new page for our custom form.
     *
     * Embed our form in a Vanilla page so that it looks nice. This example will
     * render a form when first called. You have the options to either send the
     * form or to cancel the process. Each decision will show you a different
     * screen.
     *
     * @param object $sender VanillaController.
     * @param mixed $args Arguments for our function passed in the url.
     * @return void.
     * @package HowToFormValidate
     * @since 0.1
     */
    public function vanillaController_howToFormValidate_create($sender, $args) {
        // Use themes master view.
        $sender->masterView();

        // Add common modules.
        $modules = array_diff(
                c('Modules.Vanilla.Panel'),  // The modules sort list from the config
                array_keys($sender->Assets['Panel']), // Minus: all previously attached modules
                array('MeModule') // Minus: the MeModule that is inserted by the default.master.tpl
        );
        foreach ($modules as $module) {
            // Attach each module to the panel.
            $sender->addModule($module);
        }

        $title = t('Custom Form & Validation');
        // Set page title. (We could have used $sender->setData('Title', $title);
        $sender->title($title);
        // This sets the breadcrumb to our current page.
        $sender->setData('Breadcrumbs', array(array('Name' => $title, 'Url' => 'vanilla/HowToFormValidate')));
        // Add some more info, just for the fun of it
        $sender->setData('Info', t('HowToFormValidateInfo', 'Example for common form elements and how to validate their values'));

        // Define options for the form elements.
        // In HTML you have input type text and textareas. Vanilla only has
        // TextBoxes. In order to make them multiline, you need an options
        // array like that.
        $sender->setData(
            'TextBoxOptions',
            array(
                'MultiLine' => true,
                'class' => 'TextBox',
                'rows' => 3
            )
        );

        // Some Example Data for a RadioList.
        $sender->setData(
            'RadioListData',
            array(
                '1' => t('One'),
                '2' => t('Two'),
                '3' => t('Three')
            )
        );
        // You can define which radio button should be preselected.
        $sender->setData('RadioListOptions', array('default' => '2'));

        // For a dropdown, you can use the same data as for the RadioList.
        $sender->setData('DropDownData', $sender->data('RadioListData'));
        // But for setting a standard value, you have to user "value".
        $sender->setData('DropDownOptions', array('value' => '2'));


        // The real interesting things start here. We check if this function has
        // been called by our form. If not, we have to set it up and show it.
        // If it has been called by the form and the [Cancel] button was
        // pressed, just show the "Goodbye" message. If the values have been
        // submitted, show the "Look at what you've done" screen.
        $sender->form = new Gdn_Form();

        // This is the default form. If we have a valid result, we change the
        // value of this var in order to load the result form.
        $viewName = 'form.php';


        // Check if function call includes post values.
        if ($sender->form->authenticatedPostBack()) {
            // You have access to the values of the form with this array.
            $formPostValues = $sender->form->formValues();
            // Check if Cancel button has been pressed
            if (isset($formPostValues['Cancel'])) {
                $viewName = 'canceled.php';
            } else {
                // Stuff the submitted form data back into the form that we load
                // again.
                $sender->form->setData($formPostValues);

                // Since we want to validate some fields, we have to creat a
                // validation object.
                $validation = new Gdn_Validation();

                // Now we will apply different rules for some fields. There are
                // predefined rules. You can see them in class.validation.php

                // Give field name, rule name and optionally a custom error text.
                $validation->applyRule(
                    'RequiredExample',
                    'Required',
                    t('I\'ve said the "Required" field is required...')
                );

                // We enforce the input of that field...
                $validation->applyRule(
                    'EmailExample',
                    'Required'
                );
                // ... and it has to be a valid email address (valid, not existing!)
                $validation->applyRule(
                    'EmailExample',
                    'Email'
                );

                // This field is also mandatory.
                $validation->applyRule(
                    'UserNameExample',
                    'Required'
                );
                // And it should be a username (valid, not existing!)
                $validation->applyRule(
                    'UserNameExample',
                    'Username'
                );

                // We've defined what we want to do, now start the validation
                // process.
                // if validation get's errors, we have to attach them to the form.
                if (!$validation->validate($formPostValues)) {
                    $sender->form->setValidationResults($validation->Results());
                    // We've defined defaults for two form elements. After the user
                    // has submitted the form, those defaults aren't needed any more.
                    $sender->setData('RadioListOptions', array());
                    $sender->setData('DropDownOptions', array());
                } else {
                    // We are happy with the input. You can take actions depending
                    // on the form values now: save them, display some calculated
                    // results or whatever.
                    $viewName = 'submitted.php';
                }
            }
        }
        $sender->render(parent::getView($viewName));
    }
}
