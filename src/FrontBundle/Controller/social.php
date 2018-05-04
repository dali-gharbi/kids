<?php
$message = new \MartinGeorgiev\SocialPost\Provider\Message('your test message');
$container->get('social_post')->publish($message);