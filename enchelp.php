<?php

function Encrypt($string, $key)
{
    if (extension_loaded('mcrypt') === true)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, substr($key, 0, mcrypt_get_key_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB)), trim($string), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    return false;
}

function Decrypt($string, $key)
{

	if(false){
	
		echo "<br>string:<br> $string <br>key:<br> $key";
		exit();

	}


    if (extension_loaded('mcrypt') === true)
    {
	try{

		$result = mcrypt_decrypt(MCRYPT_BLOWFISH, 
					substr($key, 0, mcrypt_get_key_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB)), 
					base64_decode($string), 
					MCRYPT_MODE_ECB, 
					mcrypt_create_iv(
						mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND)
		);

		$result = trim($result);	

	} catch (Exception $e){
		echo 'Caught exception: ',  $e->getMessage(), "\n";
		exit();
	}
        return $result;
    }

    return false;
}

function Encrypt_File($source, $destination, $key)
{
    if (extension_loaded('mcrypt') === true)
    {
        if (is_file($source) === true)
        {
                $source = file_get_contents($source);

                if (file_put_contents($destination, Encrypt($source, $key), LOCK_EX) !== false)
                {
                        return true;
                }
        }
    }

    return false;
}

function Decrypt_File($source, $destination, $key)
{
    if (extension_loaded('mcrypt') === true)
    {
        if (is_file($source) === true)
        {
                $source = file_get_contents($source);

                if (file_put_contents($destination, Decrypt($source, $key), LOCK_EX) !== false)
                {
                        return true;
                }
        }
    }

    return false;
}


?>
