<?php
/**
 * Created by PhpStorm.
 * User: gordon
 * Date: 28/4/2561
 * Time: 0:22 น.
 */

namespace Suilven\LogoutHelper\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

class LogoutController extends Controller
{
    private static $allowed_actions = ['index'];

    /**
     * Accessible via /logout
     *
     * @param $request
     */
    public function index($request)
    {
        $member = Security::getCurrentUser();
        $this->doLogOut($member);
    }

    /**
     * @param Member $member
     * @return HTTPResponse
     */
    public function doLogOut($member)
    {
        $this->extend('beforeLogout');

        if ($member instanceof Member) {
            Injector::inst()->get(IdentityStore::class)->logOut($this->getRequest());
        }

        if (Security::getCurrentUser()) {
            $this->extend('failedLogout');
        } else {
            $this->extend('afterLogout');
        }

        return $this->redirect('/');
    }
}
