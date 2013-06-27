<?php

namespace Zf2auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zf2auth\Entity\Users;
use Zf2auth\Form\UsersForm;
use Zf2auth\Form\UsersSearchForm;
use Zf2auth\Form\LoginForm;
use Zf2auth\Form\RegistrationForm;
use Zf2auth\Form\EmailCheckCodeForm;
use Zf2auth\Form\ForgetPasswordForm;
use Zf2auth\Form\ChangePasswordForm;
use Zend\Db\Sql\Select;
use Zend\Authentication\Result;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Application\Controller\AppController;

class UsersController extends AppController
{

    protected $usersTable;
    protected $storage;
    protected $authservice;

    /**
     *
     * @return type
     */
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()->get('Zf2auth\Model\Zf2AuthStorage');
        }

        return $this->storage;
    }

    /**
     *
     * @return type
     */
    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    /**
     *
     * @return type
     */
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm               = $this->getServiceLocator();
            $this->usersTable = $sm->get('Zf2auth\Table\UsersTable');
        }
        return $this->usersTable;
    }

    /**
     *
     */
    public function searchAction()
    {

        $request = $this->getRequest();

        $url = 'index';

        if ($request->isPost()) {
            $formdata    = (array) $request->getPost();
            $search_data = array();
            foreach ($formdata as $key => $value) {
                if ($key != 'submit') {
                    if (!empty($value)) {
                        $search_data[$key] = $value;
                    }
                }
            }
            if (!empty($search_data)) {
                $search_by = json_encode($search_data);
                $url .= '/search_by/' . $search_by;
            }
        }
        $this->redirect()->toUrl($url);
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

        $searchform = new UsersSearchForm();
        $searchform->get('submit')->setValue('Search');

        $select = new Select();

        $order_by  = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'id';
        $order     = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
        $page      = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $select->order($order_by . ' ' . $order);
        $search_by = $this->params()->fromRoute('search_by') ?
                $this->params()->fromRoute('search_by') : '';


        $where    = new \Zend\Db\Sql\Where();
        $formdata = array();
        if (!empty($search_by)) {
            $formdata = (array) json_decode($search_by);
            if (!empty($formdata['username'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('username', '%' . $formdata['username'] . '%')
                );
            }
            if (!empty($formdata['email'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('email', '%' . $formdata['email'] . '%')
                );
            }
            if (!empty($formdata['password'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('password', '%' . $formdata['password'] . '%')
                );
            }
            if (!empty($formdata['email_check_code'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('email_check_code', '%' . $formdata['email_check_code'] . '%')
                );
            }
            if (!empty($formdata['is_disabled'])) {
                $where->addPredicate(
                        new \Zend\Db\Sql\Predicate\Like('is_disabled', '%' . $formdata['is_disabled'] . '%')
                );
            }
        }
        if (!empty($where)) {
            $select->where($where);
        }


        $users        = $this->getUsersTable()->fetchAll($select);
        $totalRecord  = $users->count();
        $itemsPerPage = 2;

        $users->current();
        $paginator = new Paginator(new paginatorIterator($users));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(7);

        $searchform->setData($formdata);

        return new ViewModel(array(
            'search_by'   => $search_by,
            'order_by'    => $order_by,
            'order'       => $order,
            'page'        => $page,
            'paginator'   => $paginator,
            'pageAction'  => 'users',
            'form'        => $searchform,
            'totalRecord' => $totalRecord
        ));
    }

    /**
     *
     * @return type
     */
    public function addAction()
    {
        $form = new UsersForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $users = new Users();
            $form->setInputFilter($users->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $users->exchangeArray($form->getData());
                $this->getUsersTable()->saveUsers($users);

                // Redirect to list of userss
                return $this->redirect()->toRoute('users');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('users', array(
                        'action' => 'add'
            ));
        }
        $users = $this->getUsersTable()->getUsers($id);

        $form = new UsersForm();
        $form->bind($users);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($users->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUsersTable()->saveUsers($form->getData());

                // Redirect to list of userss
                return $this->redirect()->toRoute('users');
            }
        }

        return array(
            'id'   => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('users');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $id = (int) $request->getPost('id');
            $this->getUsersTable()->deleteUsers($id);


            // Redirect to list of userss
            return $this->redirect()->toRoute('users');
        }

        return array(
            'id'    => $id,
            'users' => $this->getUsersTable()->getUsers($id)
        );
    }

    public function authenticateAction()
    {
        $form     = new LoginForm();
        $redirect = 'users/login';

        $request = $this->getRequest();
        if ($request->isPost()) {

            $formData = $request->getPost();

            $form->setValidationGroup('username', 'password');
            $form->setData($formData);
            if ($form->isValid()) {
                $this->checkAuthentication($request->getPost('username'), $request->getPost('password'), $request->getPost('rememberme'), $redirect);
            } else {
                $this->flashMessenger()->addMessage(array('error' => 'Invalid Username or Password'));
            }
        }
        return $this->redirect()->toRoute($redirect);
    }

    private function checkAuthentication($username, $password, $rememberme = null, $redirect = null)
    {


        if (!empty($username) && !empty($password)) {

        } else {
            $this->flashMessenger()->addMessage(array('error' => 'Username or Password is empty.'));
            return $this->redirect()->toRoute($redirect);
        }


        //check authentication...
        $this->getAuthService()->getAdapter()
                ->setIdentity($username)
                ->setCredential($password);

        $result = $this->getAuthService()->authenticate();

        switch ($result->getCode()) {

            case Result::FAILURE_IDENTITY_NOT_FOUND:
                /** do stuff for nonexistent identity * */
                $this->flashMessenger()->addMessage(array('error' => 'Username does not exist.'));
                break;

            case Result::FAILURE_CREDENTIAL_INVALID:
                /** do stuff for invalid credential * */
                $this->flashMessenger()->addMessage(array('error' => 'Password has not matched.'));
                break;

            case Result::SUCCESS:
                /** do stuff for successful authentication * */
                // $this->flashMessenger()->addMessage(array('success' => 'Successfully logged in.'));

                break;

            default:
                /** do stuff for other failure * */
                break;
        }

        foreach ($result->getMessages() as $message) {
            //save message temporary into flashmessenger
            $this->flashMessenger()->addMessage($message);
        }

        if ($result->isValid()) {
            $redirect = 'home';
            //check if it has rememberMe :
            if ($rememberme == 1) {
                $this->getSessionStorage()->setRememberMe(1);
            }
            //set storage again
            $this->getAuthService()->setStorage($this->getSessionStorage());
            $identity       = $this->getAuthService()->getAdapter()->getIdentity();
            $currentUserObj = $this->getUsersTable()->fetchAllByIdentity($identity);
            if (!empty($currentUserObj)) {
                foreach ($currentUserObj as $user) {
                    $currentUser             = $user;
                }
            }
            $currentUser['identity'] = $username;
            $currentUser['rolename'] = 'Administrator';
            $this->getAuthService()->getStorage()->write($currentUser);
        }
        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashMessenger()->addMessage(array('success' => "You've been logged out"));
        session_destroy();
        return $this->redirect()->toRoute('home');
    }

    public function loginAction()
    {

        // $this->layout('layout/small-layout');
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new LoginForm();
        $form->get('submit')->setValue('Login');

        return array(
            'form'          => $form,
            'flashMessages' => $this->flashMessenger()->getMessages()
        );
    }

    public function registrationAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        $form = new RegistrationForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $users    = new Users();
            $form->setInputFilter($users->getInputFilter());
            $formData = $request->getPost();
            $form->setData($formData);
            if ($form->isValid()) {
                $valid = true;
                /**
                 * Check e-mail of username is exist
                 */
                if (!empty($formData['username'])) {
                    $usersobj = $this->getUsersTable()->getUsersByUserName($formData['username']);
                    if (!empty($usersobj)) {
                        $valid          = false;
                        $this->error[0] = array('error' => 'Username already used.');
                    }
                }
                if (!empty($formData['email'])) {
                    $usersobj = $this->getUsersTable()->getUsersByEmail($formData['email']);
                    if (!empty($usersobj)) {
                        $valid          = false;
                        $this->error[0] = array('error' => 'Email already used.');
                    }
                }
                /**
                 * Check password length
                 */
                if (strlen($formData['password']) < 6) {
                    $valid          = false;
                    $this->error[0] = array('error' => 'Password is too short. Password length should be minimum 6 char.');
                }
                if ($valid) {
                    $users->exchangeArray($form->getData());
                    $this->getUsersTable()->saveRegistration($users, $formData);
                    $this->checkAuthentication($request->getPost('username'), $request->getPost('password'), 0, 'users/registration');
                }
                // Redirect to list of userss
                // return $this->redirect()->toRoute('users/authenticate');
            } else {
                $this->error[0] = array('error' => 'Required field(s) are empty or invalid.');
            }
        }
        $vm             = new ViewModel(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'form'          => $form,
            'error'         => $this->error,
        ));
        return $vm;
    }

    public function confirmEmailAction()
    {
        $this->layout('layout/small-layout');
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('users', array(
                        'action' => 'add'
            ));
        }
        $users = $this->getUsersTable()->getUsers($id);
        $users->setEmail_check_code('');

        $form = new EmailCheckCodeForm();
        $form->setValidationGroup('id');
        $form->bind($users);
        $form->get('submit')->setAttribute('value', 'Enter Email Check Code');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData   = $request->getPost();
            $valid      = true;
            /**
             * Check E-mail check code
             */
            $checkusers = $this->getUsersTable()->getUsers($formData['id']);
            if ($checkusers->getEmail_check_code() == $formData['email_check_code']) {
                $formData['email_check_code'] = '';
            } else {
                $this->error[0] = array('error' => 'Invalid code.');
                $valid          = false;
            }

            $form->setInputFilter($users->getInputFilter());
            $form->setData($formData);
            if ($form->isValid()) {
                if ($valid) {
                    $this->getUsersTable()->ConfirmEmailCheckCode($form->getData());

                    $currentUser                     = array();
                    $currentUser                     = $this->getAuthService()->getStorage()->read();
                    $currentUser['email_check_code'] = $formData['email_check_code'];
                    $this->getAuthService()->getStorage()->write($currentUser);
                    return $this->redirect()->toRoute('home');
                }
            }
        }

        $vm = new ViewModel(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'id'            => $id,
            'form'          => $form,
            'error'         => $this->error,
        ));
        return $vm;
    }

    public function forgetPasswordAction()
    {
        $this->layout('layout/small-layout');
        $form = new ForgetPasswordForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $users    = new Users();
            $form->setInputFilter($users->getInputFilter());
            $formData = $request->getPost();
            $usersobj = "";
            $valid    = true;
            /**
             * Check e-mail of username is exist
             */
            if (!empty($formData['username'])) {
                $usersobj = $this->getUsersTable()->getUsersByUserName($formData['username']);
            } else if (!empty($formData['email'])) {
                $usersobj = $this->getUsersTable()->getUsersByEmail($formData['email']);
            } else {
                $valid          = false;
                $this->error[0] = array('error' => 'Username or password is empty.');
            }
            if ($valid) {
                if (!empty($usersobj)) {
                    $formData['id']       = $usersobj->id;
                    $password             = $formData['password'] = $this->getUsersTable()->generatePassword();
                } else {
                    $valid          = false;
                    $this->error[0] = array('error' => 'Invalid username or email address.');
                }
            }
            $form->setData($formData);
            $form->setValidationGroup('id', 'password');
            if ($form->isValid()) {
                if ($valid) {
                    $body    = $formData['password'];
                    $subject = "Password Reset from Hossbrag!";
                    $from    = 'team@hossbrag.com';
                    $to      = $usersobj->email;

                    $result = \HBMail\Controller\EmailersController::sendMail($body, $body, $subject, $from, $to);
                    if ($result) {
                        $users->exchangeArray($formData);
                        $result1 = $this->getUsersTable()->resetPassword($users);
                        $this->flashMessenger()->addMessage(array('success' => 'Your password has reset and sent to your e-mail!'));
                        return $this->redirect()->toRoute('users/forget-password');
                    } else {
                        $this->error[0] = array('error' => 'Can not sent email.');
                    }
                }
            }
            // $this->error[0] = array('error' => 'Invalid Information');
        }
        $vm             = new ViewModel(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'form'          => $form,
            'error'         => $this->error,
        ));
        return $vm;
    }

    public function changePasswordAction()
    {
        $this->layout('layout/inner-layout');


        $id    = (int) $this->getCurrentUser()->id;
        $users = $this->getUsersTable()->getUsers($id);

        $form = new ChangePasswordForm();
        $form->setValidationGroup('id');
        $form->bind($users);
        $form->get('submit')->setAttribute('value', 'Change Password');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $users    = new Users();
            $form->setInputFilter($users->getInputFilter());
            $formData = $request->getPost();
            $valid    = true;

            /**
             * Check whether userid is exist
             */
            $usersobj = $this->getUsersTable()->getUsers($formData['id']);
            if (empty($usersobj) || $formData['id'] != $id) {
                $valid          = false;
                $this->error[0] = array('error' => 'Unauthorized access. User does not exist.');
            }
            /**
             * Check whether password is valid
             */
            if (MD5($formData['cpassword']) != $usersobj->password) {
                $valid          = false;
                $this->error[0] = array('error' => 'Unauthorized access. Password does not matched.');
            }

            if ($formData['password'] != $formData['repassword']) {
                $valid          = false;
                $this->error[0] = array('error' => 'Password does not matched.');
            }

            if (strlen($formData['password']) < 6) {
                $valid          = false;
                $this->error[0] = array('error' => 'Password is too short. Password length should be minimum 6 char.');
            }

            if (empty($usersobj->email)) {
                $valid          = false;
                $this->error[0] = array('error' => 'Username or password is empty.');
            }

            $form->setData($formData);
            $form->setValidationGroup('id', 'password');

            if ($form->isValid()) {

                if ($valid) {
                    $body    = $formData['password'];
                    $subject = "Password Changed successfully!";
                    $from    = 'team@hossbrag.com';
                    $to      = $usersobj->email;


                    $users->exchangeArray($formData);
                    $this->getUsersTable()->changePassword($users);
                    $this->flashMessenger()->addMessage(array('success' => 'Your password has changed successfully!'));
                    $result = \HBMail\Controller\EmailersController::sendMail($body, $body, $subject, $from, $to);
                    if ($result) {

                    } else {
                        $this->error[0] = array('error' => 'Can not sent email.');
                    }
                    return $this->redirect()->toRoute('users/change-password');
                }
            }
            // $this->error[0] = array('error' => 'Invalid Information');
        }
        $vm             = new ViewModel(array(
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'form'          => $form,
            'error'         => $this->error,
        ));
        return $vm;
    }

}

