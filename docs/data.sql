-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Хост: 192.168.122.6
-- Время создания: Сен 26 2014 г., 11:19
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

--
-- Дамп данных таблицы `access_origin`
--

INSERT INTO `access_origin` (`id`, `value`) VALUES
(1, '*');

--
-- Дамп данных таблицы `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `secret`, `name`, `auto_approve`) VALUES
('admin', 'secret', 'admin', 0);

--
-- Дамп данных таблицы `upload_path`
--

INSERT INTO `upload_path` (`id`, `path`) VALUES
(1, '/mnt/share/storage/');

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin');
