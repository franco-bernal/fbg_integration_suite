<?php
/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2024 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
$sql = array();

// Crear la tabla fbg_integration_suite_app
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fbg_integration_suite_app` (
    `id_app` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `url` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id_app`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;';

// Crear la tabla fbg_integration_suite_hooks
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fbg_integration_suite_hooks` (
    `id_hook` INT NOT NULL AUTO_INCREMENT,
    `id_app` INT NOT NULL,
    `hooks` TEXT NOT NULL,
    PRIMARY KEY (`id_hook`),
    FOREIGN KEY (`id_app`) REFERENCES `' . _DB_PREFIX_ . 'fbg_integration_suite_app`(`id_app`) ON DELETE CASCADE
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;';

// Ejecutar las consultas SQL
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}