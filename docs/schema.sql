--
-- Структура таблицы `access_origin`
--

CREATE TABLE IF NOT EXISTS `access_origin` (
  `id` int(10) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `parent` varchar(512) NOT NULL,
  `name` varchar(256) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `extension` varchar(8) NOT NULL,
  `data` longblob,
  `added` timestamp NULL DEFAULT NULL,
  `checked` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `folder`
--

CREATE TABLE IF NOT EXISTS `folder` (
  `id` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `parent` varchar(512) NOT NULL,
  `name` varchar(256) NOT NULL,
  `added` timestamp NULL DEFAULT NULL,
  `checked` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` int(10) unsigned NOT NULL,
  `file_id` int(10) unsigned NOT NULL,
  `data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_clients`
--

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `secret` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auto_approve` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_client_endpoints`
--

CREATE TABLE IF NOT EXISTS `oauth_client_endpoints` (
  `id` int(10) unsigned NOT NULL,
  `client_id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `id` smallint(5) unsigned NOT NULL,
  `scope` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_sessions`
--

CREATE TABLE IF NOT EXISTS `oauth_sessions` (
  `id` int(10) unsigned NOT NULL,
  `client_id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `owner_type` enum('user','client') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `owner_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5363 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_access_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_session_access_tokens` (
  `id` int(10) unsigned NOT NULL,
  `session_id` int(10) unsigned NOT NULL,
  `access_token` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_expires` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5360 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_authcodes`
--

CREATE TABLE IF NOT EXISTS `oauth_session_authcodes` (
  `id` int(10) unsigned NOT NULL,
  `session_id` int(10) unsigned NOT NULL,
  `auth_code` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `auth_code_expires` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_authcode_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth_session_authcode_scopes` (
  `oauth_session_authcode_id` int(10) unsigned NOT NULL,
  `scope_id` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_redirects`
--

CREATE TABLE IF NOT EXISTS `oauth_session_redirects` (
  `session_id` int(10) unsigned NOT NULL,
  `redirect_uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_refresh_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_session_refresh_tokens` (
  `session_access_token_id` int(10) unsigned NOT NULL,
  `refresh_token` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `refresh_token_expires` int(10) unsigned NOT NULL,
  `client_id` char(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth_session_token_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth_session_token_scopes` (
  `id` bigint(20) unsigned NOT NULL,
  `session_access_token_id` int(10) unsigned DEFAULT NULL,
  `scope_id` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(8) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `share_file`
--

CREATE TABLE IF NOT EXISTS `share_file` (
  `id` int(10) unsigned NOT NULL,
  `share_id` int(10) unsigned NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `upload_path`
--

CREATE TABLE IF NOT EXISTS `upload_path` (
  `id` int(11) NOT NULL,
  `path` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` varchar(16) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `access_origin`
--
ALTER TABLE `access_origin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `u_oacl_clse_clid` (`secret`,`id`);

--
-- Индексы таблицы `oauth_client_endpoints`
--
ALTER TABLE `oauth_client_endpoints`
  ADD PRIMARY KEY (`id`), ADD KEY `i_oaclen_clid` (`client_id`);

--
-- Индексы таблицы `oauth_scopes`
--
ALTER TABLE `oauth_scopes`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `u_oasc_sc` (`scope`);

--
-- Индексы таблицы `oauth_sessions`
--
ALTER TABLE `oauth_sessions`
  ADD PRIMARY KEY (`id`), ADD KEY `i_uase_clid_owty_owid` (`client_id`,`owner_type`,`owner_id`);

--
-- Индексы таблицы `oauth_session_access_tokens`
--
ALTER TABLE `oauth_session_access_tokens`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `u_oaseacto_acto_seid` (`access_token`,`session_id`), ADD KEY `f_oaseto_seid` (`session_id`);

--
-- Индексы таблицы `oauth_session_authcodes`
--
ALTER TABLE `oauth_session_authcodes`
  ADD PRIMARY KEY (`id`), ADD KEY `session_id` (`session_id`);

--
-- Индексы таблицы `oauth_session_authcode_scopes`
--
ALTER TABLE `oauth_session_authcode_scopes`
  ADD KEY `oauth_session_authcode_id` (`oauth_session_authcode_id`), ADD KEY `scope_id` (`scope_id`);

--
-- Индексы таблицы `oauth_session_redirects`
--
ALTER TABLE `oauth_session_redirects`
  ADD PRIMARY KEY (`session_id`);

--
-- Индексы таблицы `oauth_session_refresh_tokens`
--
ALTER TABLE `oauth_session_refresh_tokens`
  ADD PRIMARY KEY (`session_access_token_id`), ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `oauth_session_token_scopes`
--
ALTER TABLE `oauth_session_token_scopes`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `u_setosc_setoid_scid` (`session_access_token_id`,`scope_id`), ADD KEY `f_oasetosc_scid` (`scope_id`);

--
-- Индексы таблицы `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `share_file`
--
ALTER TABLE `share_file`
  ADD PRIMARY KEY (`id`), ADD KEY `share_id` (`share_id`);

--
-- Индексы таблицы `upload_path`
--
ALTER TABLE `upload_path`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`role`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `access_origin`
--
ALTER TABLE `access_origin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `file`
--
ALTER TABLE `file`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `folder`
--
ALTER TABLE `folder`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `image`
--
ALTER TABLE `image`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `oauth_client_endpoints`
--
ALTER TABLE `oauth_client_endpoints`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `oauth_scopes`
--
ALTER TABLE `oauth_scopes`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `oauth_sessions`
--
ALTER TABLE `oauth_sessions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5363;
--
-- AUTO_INCREMENT для таблицы `oauth_session_access_tokens`
--
ALTER TABLE `oauth_session_access_tokens`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5360;
--
-- AUTO_INCREMENT для таблицы `oauth_session_authcodes`
--
ALTER TABLE `oauth_session_authcodes`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `oauth_session_token_scopes`
--
ALTER TABLE `oauth_session_token_scopes`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `share`
--
ALTER TABLE `share`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `share_file`
--
ALTER TABLE `share_file`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT для таблицы `upload_path`
--
ALTER TABLE `upload_path`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
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
