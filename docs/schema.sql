-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Хост: 192.168.122.6
-- Время создания: Сен 26 2014 г., 11:15
-- Версия сервера: 5.5.38
-- Версия PHP: 5.4.4-14+deb7u12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `mediabox_storage`
--

-- --------------------------------------------------------

--
-- Структура таблицы `access_origin`
--

CREATE TABLE IF NOT EXISTS `access_origin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `parent` varchar(512) NOT NULL,
  `name` varchar(256) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `extension` varchar(8) NOT NULL,
  `data` longblob,
  `added` timestamp NULL DEFAULT NULL,
  `checked` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29182 ;

-- --------------------------------------------------------

--
-- Структура таблицы `folder`
--

CREATE TABLE IF NOT EXISTS `folder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `parent` varchar(512) NOT NULL,
  `name` varchar(256) NOT NULL,
  `added` timestamp NULL DEFAULT NULL,
  `checked` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1902 ;

-- --------------------------------------------------------

--
-- Структура таблицы `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(10) unsigned NOT NULL,
  `data` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_clients`
--

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `secret` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auto_approve` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_oacl_clse_clid` (`secret`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_client_endpoints`
--

CREATE TABLE IF NOT EXISTS `oauth_client_endpoints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `i_oaclen_clid` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `scope` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_oasc_sc` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_sessions`
--

CREATE TABLE IF NOT EXISTS `oauth_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `owner_type` enum('user','client') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `owner_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `i_uase_clid_owty_owid` (`client_id`,`owner_type`,`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_access_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_session_access_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` int(10) unsigned NOT NULL,
  `access_token` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_oaseacto_acto_seid` (`access_token`,`session_id`),
  KEY `f_oaseto_seid` (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_authcodes`
--

CREATE TABLE IF NOT EXISTS `oauth_session_authcodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` int(10) unsigned NOT NULL,
  `auth_code` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `auth_code_expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_authcode_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth_session_authcode_scopes` (
  `oauth_session_authcode_id` int(10) unsigned NOT NULL,
  `scope_id` smallint(5) unsigned NOT NULL,
  KEY `oauth_session_authcode_id` (`oauth_session_authcode_id`),
  KEY `scope_id` (`scope_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_redirects`
--

CREATE TABLE IF NOT EXISTS `oauth_session_redirects` (
  `session_id` int(10) unsigned NOT NULL,
  `redirect_uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_refresh_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_session_refresh_tokens` (
  `session_access_token_id` int(10) unsigned NOT NULL,
  `refresh_token` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `refresh_token_expires` int(10) unsigned NOT NULL,
  `client_id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_access_token_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_token_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth_session_token_scopes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_access_token_id` int(10) unsigned DEFAULT NULL,
  `scope_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_setosc_setoid_scid` (`session_access_token_id`,`scope_id`),
  KEY `f_oasetosc_scid` (`scope_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `upload_path`
--

CREATE TABLE IF NOT EXISTS `upload_path` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `oauth_client_endpoints`
--
ALTER TABLE `oauth_client_endpoints`
  ADD CONSTRAINT `f_oaclen_clid` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `oauth_sessions`
--
ALTER TABLE `oauth_sessions`
  ADD CONSTRAINT `f_oase_clid` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `oauth_session_access_tokens`
--
ALTER TABLE `oauth_session_access_tokens`
  ADD CONSTRAINT `f_oaseto_seid` FOREIGN KEY (`session_id`) REFERENCES `oauth_sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `oauth_session_authcodes`
--
ALTER TABLE `oauth_session_authcodes`
  ADD CONSTRAINT `oauth_session_authcodes_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `oauth_sessions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `oauth_session_authcode_scopes`
--
ALTER TABLE `oauth_session_authcode_scopes`
  ADD CONSTRAINT `oauth_session_authcode_scopes_ibfk_1` FOREIGN KEY (`oauth_session_authcode_id`) REFERENCES `oauth_session_authcodes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `oauth_session_authcode_scopes_ibfk_2` FOREIGN KEY (`scope_id`) REFERENCES `oauth_scopes` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `oauth_session_redirects`
--
ALTER TABLE `oauth_session_redirects`
  ADD CONSTRAINT `f_oasere_seid` FOREIGN KEY (`session_id`) REFERENCES `oauth_sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `oauth_session_refresh_tokens`
--
ALTER TABLE `oauth_session_refresh_tokens`
  ADD CONSTRAINT `f_oasetore_setoid` FOREIGN KEY (`session_access_token_id`) REFERENCES `oauth_session_access_tokens` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `oauth_session_refresh_tokens_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `oauth_session_token_scopes`
--
ALTER TABLE `oauth_session_token_scopes`
  ADD CONSTRAINT `f_oasetosc_scid` FOREIGN KEY (`scope_id`) REFERENCES `oauth_scopes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `f_oasetosc_setoid` FOREIGN KEY (`session_access_token_id`) REFERENCES `oauth_session_access_tokens` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
