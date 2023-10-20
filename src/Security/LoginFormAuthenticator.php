<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $mailerInterface;

    public function __construct(private UrlGeneratorInterface $urlGenerator, private Security $security, MailerInterface $mailerInterface)
    {
        $this->mailerInterface = $mailerInterface;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }


        $user = $this->security->getUser();

        $email = (new Email())
        ->from(new Address($_ENV['MAIL_FROM'], 'Mailtrap'))
	    ->to('newuser@example.com')
	    ->cc('mailtrapqa@example.com')
	    ->addCc('staging@example.com')
	    ->bcc('mailtrapdev@example.com')
	    ->replyTo('mailtrap@example.com')
	    ->subject('New user logged in: ' . $user->getUserIdentifier())
 	    ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. Mailtrap’s Guide on How to Build HTML Email is live on our blog')
         ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailerInterface->send($email);
        
        
        
        if ( !in_array('ROLE_ADMIN',$user->getRoles()) ){
            return new RedirectResponse($this->urlGenerator->generate('app_home')); 
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
