--
-- Table structure for table `#__empresas_categories`
--

CREATE TABLE IF NOT EXISTS `#__empresas_categories` (
  `empresas_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` bigint(20) unsigned NOT NULL,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT 0,
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`empresas_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `#__empresas_companies`
--

CREATE TABLE IF NOT EXISTS `#__empresas_companies` (
  `empresas_company_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localizacao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` bigint(20) unsigned NOT NULL,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT 0,
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`empresas_company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;


--
-- Table structure for table `#__empresas_categories_companies`
--

CREATE TABLE IF NOT EXISTS `#__empresas_categories_companies` (
  `empresas_category_company_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`empresas_category_company_id`),
  KEY `categoria_empresa_categoria_id_index` (`category_id`),
  KEY `categoria_empresa_empresa_id_index` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;


ALTER TABLE `#__empresas_categories_companies`
  ADD CONSTRAINT `categoria_empresa_categoria_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `#__empresas_categories` (`empresas_category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `categoria_empresa_empresa_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `#__empresas_companies` (`empresas_company_id`) ON DELETE CASCADE;
