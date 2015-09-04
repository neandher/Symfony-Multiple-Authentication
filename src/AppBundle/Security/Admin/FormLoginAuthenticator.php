<?php

namespace AppBundle\Security\Admin;

use AppBundle\DomainManager\Admin\Acesso\AdminUserManager;
use KnpU\Guard\Authenticator\AbstractFormLoginAuthenticator;
use KnpU\Guard\Exception\CustomAuthenticationException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var AdminUserManager
     */
    private $adminUserManager;

    public function __construct(Router $router, UserPasswordEncoder $passwordEncoder, AdminUserManager $adminUserManager)
    {
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->adminUserManager = $adminUserManager;
    }

    /**
     * Return the URL to the login page
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('admin_security_login');
    }

    /**
     * The user will be redirected to the secure page they originally tried
     * to access. But if no such page exists (i.e. the user went to the
     * login page directly), this returns the URL the user should be redirected
     * to after logging in successfully (e.g. your homepage)
     *
     * @return string
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('admin_dashboard');
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array). If you return null, authentication
     * will be skipped.
     *
     * Whatever value you return here will be passed to getUser() and checkCredentials()
     *
     * For example, for a form login, you might:
     *
     *      return array(
     *          'username' => $request->request->get('_username'),
     *          'password' => $request->request->get('_password'),
     *      );
     *
     * Or for an API token that's on a header, you might use:
     *
     *      return array('api_key' => $request->headers->get('X-API-TOKEN'));
     *
     * @param Request $request
     *
     * @return mixed|null
     */
    public function getCredentials(Request $request)
    {
        if($request->getPathInfo() != '/admin/login_check'){
            return;
        }

        $email = $request->request->get('login')['email'];
        $request->getSession()->set(Security::LAST_USERNAME, $email);
        $password = $request->request->get('login')['password'];

        return array(
            'email' => $email,
            'password' => $password
        );
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @throws AuthenticationException
     *
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];

        $user = $this->adminUserManager->findUserByEmail($email);

        if(is_null($user)){
            throw CustomAuthenticationException::createWithSafeMessage('security.login.errors.email_not_found');
        }

        return $user;
    }

    /**
     * Throw an AuthenticationException if the credentials are invalid.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @throws CustomAuthenticationException
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];
        $encoder = $this->passwordEncoder;
        if (!$encoder->isPasswordValid($user, $plainPassword)) {
            throw CustomAuthenticationException::createWithSafeMessage('security.login.errors.password_invalid');
        }
    }
}