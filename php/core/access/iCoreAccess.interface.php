<?php
/**
 * V. 1.1
 */
interface iCoreAccess {
	/**
	 * Aktualny użytkownik jest inicjalizowany z sesji w konstruktorze
	 */
	public function __construct();

	/**
	 * @return true w przypadku powodzenia, wpp False.
	 * Można tej metody uzywać nawet kiedy użytkownik jest zalogowany,
	 * nie powinno to powodować błędu a odświeżenie danych użytkownika przechowywanych w sesji
	 * i dostępnych przez getCurrentUserData().
	 */
	public function login($userName, $password);

	public function logout();

	public function getCurrentUserId();

	// public function getCurrentUserName();

	public function getCurrentUserData();
}
?>