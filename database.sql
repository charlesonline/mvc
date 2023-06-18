/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.3.13-MariaDB : Database - projeto_mvc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`projeto_mvc` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `projeto_mvc`;

/*Table structure for table `depoimentos` */

DROP TABLE IF EXISTS `depoimentos`;

CREATE TABLE `depoimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `depoimentos` */

insert  into `depoimentos`(`id`,`nome`,`mensagem`,`data`) values 
(1,'CHARLES','MUITO LEGAL O SITE','2023-06-14 09:54:15'),
(2,'JULIO','LEGAL','2023-06-14 09:54:26'),
(3,'LUCI','BOM, DA PRA FAZER MELHOR','2023-06-14 09:54:39'),
(4,'NICOLE','MAIS OU MENOS','2023-06-14 09:54:49'),
(5,'CHARLES','OK.. BEM MELHOR AGORA','2023-06-14 09:55:07');

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id`,`nome`,`email`,`senha`) values 
(1,'charles','c@c.c','$2y$10$HoNpGPq1uUoZfQRXfjJ4ceXd0Awyv00u4dLu5LcI7FbnQjXU4FzE.');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
