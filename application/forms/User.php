<?php
class Form_User extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        // create new element
        // id is important for CRUD, when you update you need to fill it with something, don't you?
        $id = $this->createElement('hidden', 'id');
        $this->addElement($id);
        //create the form elements
        $email = $this->createElement('text','email');
        $email->setLabel('Email: ');
        $email->addValidator('emailAddress');
        $email->setRequired('true');
        $email->addFilter('StripTags');
        $email->addErrorMessage('Field is empty or not a valid email address.');
        $this->addElement($email);
        
        $password = $this->createElement('password', 'password');
        $password->setLabel('Password: ');
        $password->setRequired('true');
        $password->addErrorMessage('Field cannot be empty');
        $this->addElement($password);
        
        $url = $this->createElement('text','url');
        $url->setLabel('Domain (dmn.com): ');
        $url->setRequired('true');
        $url->addFilter('StripTags');
        $url->addValidator(new Zend_Validate_Hostname);
        $url->addErrorMessage('Field must be a valid domain');
        $this->addElement($url);
        
        $name = $this->createElement('text','name');
        $name->setLabel('Name: ');
        $name->setRequired('true');
        $name->addFilter('StripTags');
        $name->addErrorMessage('Field cannot be empty');
        $this->addElement($name);
        
        $lang = $this->createElement('multiCheckbox', 'languages');
        $lang->setLabel('Languages: ');
        $lang->addMultioption('spanish', 'Spanish');
        $lang->addMultioption('chinese', 'Chinese');
        $lang->addMultioption('portuguese', 'Portuguese');
        $lang->addMultioption('english', 'English');
        $lang->addMultioption('italian', 'Italian');
        $lang->helper = 'formMultiCheckboxList';
        $this->addElement($lang);
        
        $role = $this->createElement('select', 'role');
        $role->setLabel("Select user role:");
        $role->addMultiOption('user', 'user');
        $role->addMultiOption('admin', 'admin');
        $this->addElement($role);
        
        $this->setAttrib('id', 'alta');
        
        $submit = $this->addElement('submit', 'submit', array('label' => 'Save'));
    }
}
?>
