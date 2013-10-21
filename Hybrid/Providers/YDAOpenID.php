<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2011 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Providers_YDA OpenID based
 * 
 * Provided as a way to keep backward compatibility for Google OpenID based on HybridAuth <= 2.0.8 
 * 
 * http://hybridauth.sourceforge.net/userguide/IDProvider_info_Google.html
 */
class Hybrid_Providers_YDAOpenID extends Hybrid_Provider_Model_OpenID
{
	var $openidIdentifier = "https://yourdoctorsadvice.org/index.php/openid/index/"; 
}
