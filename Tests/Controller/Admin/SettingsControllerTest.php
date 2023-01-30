<?php

namespace BaksDev\Settings\Main\Tests\Controller\Admin;

use BaksDev\Settings\Main\Controller\Admin\SettingsController;
use BaksDev\Users\User\Tests\TestUserAccount;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SettingsControllerTest extends WebTestCase
{
	/** @link SettingsController */
	private string $controller = '/admin/settings/main';
	
	
	/** Доступ для GUEST пользователей */
	public function testRoleGuest() : void
	{
		$client = static::createClient();
		$crawler = $client->request('GET', $this->controller);
		self::assertResponseStatusCodeSame(401); // Пользователь не авторизован
	}
	
	
	/** Доступ для USER пользователей */
	public function testRoleUser() : void
	{
		$client = static::createClient();
		$user = TestUserAccount::getUser();
		
		$client->loginUser($user, 'user');
		
		$crawler = $client->request('GET', $this->controller);
		self::assertResponseStatusCodeSame(403); // Пользователь не имеет доступа
	}
	
	
	/** Доступ для ADMIN пользователей */
	public function testRoleAdmin() : void
	{
		$client = static::createClient();
		$user = TestUserAccount::getAdmin();
		
		$client->loginUser($user, 'user');
		
		$crawler = $client->request('GET', $this->controller);
		self::assertResponseIsSuccessful(); // Доступ открыт
	}
	
}