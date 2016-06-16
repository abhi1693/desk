<?php

use desk\app\web\Application;

/**
 * Desk is helper class serving common Desk and Yii2 framework functionality.
 *
 * It encapsulates [[Yii]] and ultimately [[YiiBase]], which provides the actual implementation.
 *
 * @author Abhimanyu Saharan
 */
class Desk extends Yii
{
	// Editions
	const Starter = 0;

	/** @var  Application The application instance */
	public static $app;
}

spl_autoload_register(['Desk', 'autoload'], TRUE, TRUE);