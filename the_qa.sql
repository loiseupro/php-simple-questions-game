-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-08-2022 a las 14:39:13
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `the_qa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `value` text NOT NULL,
  `correct` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `answer`
--

INSERT INTO `answer` (`id`, `id_question`, `value`, `correct`, `created`) VALUES
(1, 1, 'Yes', 1, '2022-08-02 08:45:22'),
(2, 1, 'No', 0, '2022-08-02 08:45:22'),
(3, 2, 'Server side', 1, '2022-08-02 08:50:46'),
(4, 2, 'Client side', 0, '2022-08-02 08:50:46'),
(5, 3, 'Hypertext Preprocessor', 1, '2022-08-02 09:05:09'),
(6, 3, 'Hyper Professor', 0, '2022-08-02 09:05:09'),
(7, 4, 'Yes', 0, '2022-08-02 09:07:17'),
(8, 4, 'No', 1, '2022-08-02 09:07:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `value` text NOT NULL,
  `views` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `question`
--

INSERT INTO `question` (`id`, `value`, `views`, `created`) VALUES
(1, 'Is PHP a programming language?', 0, '2022-08-02 08:44:55'),
(2, 'Where is PHP used?', 0, '2022-08-02 08:49:57'),
(3, 'What do the acronyms PHP stand for?', 0, '2022-08-02 09:04:14'),
(4, 'Does PHP support multiple inheritance?', 0, '2022-08-02 09:06:57');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
