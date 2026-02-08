-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-02-2026 a las 01:33:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_alumnos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_p` varchar(100) NOT NULL,
  `apellido_m` varchar(100) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `apellido_p`, `apellido_m`, `grupo_id`, `created_at`, `activo`) VALUES
(1, 'Bernardo David', 'Medina', 'Sanchez', 2, '2026-02-04 01:18:55', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id`, `nombre`, `codigo`, `created_at`, `activo`) VALUES
(1, 'Administración de Empresas', 'ADE', '2026-02-04 01:13:28', 1),
(2, 'Administración de Empresas Turísticas', 'AET', '2026-02-04 01:13:28', 1),
(3, 'Relaciones Internacionales', 'REL', '2026-02-04 01:13:28', 1),
(4, 'Contaduría Pública y Finanzas', 'CPF', '2026-02-04 01:13:28', 1),
(5, 'Derecho', 'DER', '2026-02-04 01:13:28', 1),
(6, 'Mercadotecnia y Publicidad', 'MEP', '2026-02-04 01:13:28', 1),
(7, 'Gastronomía', 'GAS', '2026-02-04 01:13:28', 1),
(8, 'Periodismo y Ciencias de la Comunicación', 'PCC', '2026-02-04 01:13:28', 1),
(9, 'Diseño de Modas', 'DMO', '2026-02-04 01:13:28', 1),
(10, 'Pedagogía', 'PED', '2026-02-04 01:13:28', 1),
(11, 'Cultura Física y Educación del Deporte', 'CED', '2026-02-04 01:13:28', 1),
(12, 'Idiomas (Inglés y Francés)', 'IDF', '2026-02-04 01:13:28', 1),
(13, 'Psicología', 'PSI', '2026-02-04 01:13:28', 1),
(14, 'Diseño de Interiores', 'DIN', '2026-02-04 01:13:28', 1),
(15, 'Diseño Gráfico', 'DGR', '2026-02-04 01:13:28', 1),
(16, 'Ingeniería en Logística y Transporte', 'ILT', '2026-02-04 01:13:28', 1),
(17, 'Ingeniero Arquitecto', 'IAR', '2026-02-04 01:13:28', 1),
(18, 'Informática Administrativa y Fiscal', 'INF', '2026-02-04 01:13:28', 1),
(19, 'Ingeniería en Sistemas Computacionales', 'ISC', '2026-02-04 01:13:28', 1),
(20, 'Ingeniería Mecánica Automotriz', 'IMA', '2026-02-04 01:13:28', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id`, `numero`, `descripcion`, `created_at`) VALUES
(1, 1, 'Primer Semestre', '2026-02-04 01:15:41'),
(2, 2, 'Segundo Semestre', '2026-02-04 01:15:41'),
(3, 3, 'Tercer Semestre', '2026-02-04 01:15:41'),
(4, 4, 'Cuarto Semestre', '2026-02-04 01:15:41'),
(5, 5, 'Quinto Semestre', '2026-02-04 01:15:41'),
(6, 6, 'Sexto Semestre', '2026-02-04 01:15:41'),
(7, 7, 'Séptimo Semestre', '2026-02-04 01:15:41'),
(8, 8, 'Octavo Semestre', '2026-02-04 01:15:41'),
(9, 9, 'Noveno Semestre', '2026-02-04 01:15:41'),
(10, 10, 'Decimo Semestre', '2026-02-04 01:15:41'),
(11, 11, 'Onceavo Semestre', '2026-02-04 01:15:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `carrera_id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  `grado_id` int(11) NOT NULL,
  `numero_grupo` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `nombre`, `carrera_id`, `turno_id`, `grado_id`, `numero_grupo`, `created_at`) VALUES
(1, 'ISC1101-M', 19, 7, 11, 1, '2026-02-04 01:17:21'),
(2, 'ISC1102-M', 19, 7, 11, 2, '2026-02-04 01:17:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `codigo` varchar(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `nombre`, `codigo`, `created_at`) VALUES
(7, 'Matutino', 'M', '2026-02-04 01:16:26'),
(8, 'Vespertino', 'V', '2026-02-04 01:16:26'),
(9, 'Mixto', 'X', '2026-02-04 01:16:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_grupo` (`grupo_id`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `turno_id` (`turno_id`),
  ADD KEY `grado_id` (`grado_id`),
  ADD KEY `idx_grupo_config` (`carrera_id`,`turno_id`,`grado_id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grupos_ibfk_2` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grupos_ibfk_3` FOREIGN KEY (`grado_id`) REFERENCES `grados` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
