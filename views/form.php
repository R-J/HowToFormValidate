<?php defined('APPLICATION') or die;

// Prepare some variables so that the form will become more readable.

// In HTML you have input type text and textareas. Vanilla only has TextBoxes.
// In order to make them multiline, you need an options array like that.
$textBoxOptions = $this->data('TextBoxOptions');

// Some Example Data for a RadioList.
$radioListData = $this->data('RadioListData');
// You can define which radio button should be preselected.
$radioListOptions = $this->data('RadioListOptions');

// For a dropdown, you can use the same data as for the RadioList.
$dropDownData = $radioListData;
// But for setting a standard value, you have to user "value".
$dropDownOptions = $this->data('DropDownOptions');


?>


<!-- first we define some markup -->
<h1><?= $this->data('Title') ?></h1>
<div class="Info"><?= $this->data('Info') ?></div>

<?php
  // This "starts" our form.
  echo $this->form->open();
  // Show errors here, at the top of the form. 
  echo $this->form->errors();
?>

<!-- TextBox with MultiLine => textaread -->
<div class="P">
  <?= $this->form->label('This MultiLine TextBox', 'TextBoxExample') ?>
  <div class="TextBoxWrapper">
    <?= $this->form->textBox('TextBoxExample', $textBoxOptions) ?>
  </div>
</div>
<hr>
<!-- RadioList => input type radio -->
<div class="P">
  <?= $this->form->label('Now that\'s input type="radio"', 'RadioListExample') ?>
  <?= $this->form->radioList('RadioListExample', $radioListData, $radioListOptions) ?>
</div>
<hr>
<!-- DropDown => select/options -->
<div class="P">
  <?= $this->form->label('You can have that also as a select list', 'DropDownExample') ?>
  <?= $this->form->dropDown('DropDownExample', $dropDownData, $dropDownOptions) ?>
</div>
<hr>
<!-- DropDown => select/options -->
<div class="P">
  <?= $this->form->label('This is a helpful shortcut for Categories!', 'CategoryDropDownExample') ?>
  <?= $this->form->categoryDropDown('CategoryDropDownExample') ?>
</div>
<hr>
<!-- Required -->
<div class="P">
  <?= $this->form->label('Let\'s do validation! The following field is required!', 'RequiredExample') ?>
  <?= $this->form->textBox('RequiredExample', array('class' => 'TextBox')) ?>
</div>
<hr>
<!-- email -->
<div class="P">
  <?= $this->form->label('Enter a valid email address', 'EmailExample') ?>
  <?= $this->form->textBox('EmailExample', array('class' => 'TextBox')) ?>
</div>
<hr>
<!-- username -->
<div class="P">
  <?= $this->form->label('Enter a valid user name!', 'UserNameExample') ?>
  <?= $this->form->textBox('UserNameExample', array('class' => 'TextBox')) ?>
</div>


<!-- Button => input type submit -->
<div class="P">
    <?= $this->form->button('Submit') ?>
    <?= $this->form->button('Cancel') ?>
</div>
