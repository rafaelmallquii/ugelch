<?php
/**
 * Noticias iniciales: tres notas reales publicadas por la UGEL Chincheros en gob.pe.
 * Se crean una sola vez al activar el tema (si no existe ya una entrada con ese slug).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ugelch_seed_posts_data() {
	return array(
		array(
			'slug'    => 'inician-construccion-de-moderna-infraestructura-educativa-en-pumachuco',
			'title'   => 'Inician construcción de moderna infraestructura educativa en Pumachuco',
			'date'    => '2025-04-09 12:02:00',
			'image'   => 'pumachuco-infraestructura-educativa.jpg',
			'alt'     => 'Autoridades, docentes y comunidad de Pumachuco en la colocación de la primera piedra de la I.E. 54223',
			'excerpt' => 'Con la colocación de la primera piedra comenzó la obra de mejoramiento de la I.E. 54223 del centro poblado de Pumachuco, en Huaccana: seis aulas, biblioteca, aula de innovación y losa deportiva, entre otros ambientes.',
			'source'  => 'https://www.gob.pe/institucion/ugelchincheros/noticias/1142765-inician-construccion-de-moderna-infraestructura-educativa-en-pumachuco',
			'content' => array(
				'El lunes 7 de abril se colocó la primera piedra y se dio inicio a la obra del proyecto <strong>“Mejoramiento del Servicio de Educación Primaria en la I.E. 54223 del Centro Poblado de Pumachuco”</strong>, en el distrito de Huaccana. La ceremonia contó con la participación de estudiantes, profesores, padres de familia, población en general y el director de la UGEL Chincheros.',
				'## Qué incluye el proyecto',
				'La obra es un proyecto integral que comprende la construcción de:',
				'- seis aulas, biblioteca y aula de innovación pedagógica;|- módulo de conectividad y ambiente para taller creativo;|- sala de usos múltiples y losa deportiva con cobertura;|- estacionamiento para bicicletas y áreas verdes;|- dirección, sala de profesores, sala de reuniones, sala de espera, archivo y ambientes administrativos;|- cocinas, depósitos, almacén, vestidores para niños, servicios higiénicos y cerco perimétrico.',
				'De acuerdo con el expediente técnico, la obra se entregará en <strong>seis meses</strong> y cuenta con un presupuesto de <strong>S/ 7 161 480,00</strong>.',
				'## Compromiso de las autoridades',
				'El alcalde distrital de Huaccana, Lic. Gilber Rojas Allende, saludó la preocupación del director, los profesores, los padres de familia y las autoridades locales de Pumachuco, y expresó su compromiso de cumplir el proyecto de acuerdo con el expediente técnico, en el plazo establecido y con todos sus componentes.',
				'> «Con estas condiciones de educabilidad, nuestros estudiantes lograrán ser estudiantes exitosos con mentalidad transformadora».|— Dr. Luis Moisés Sánchez Vergara, director de la UGEL Chincheros',
				'El director de la UGEL Chincheros mostró su alegría por la nueva infraestructura que tendrán los estudiantes y agradeció al alcalde distrital. Con la ejecución de este proyecto, la primera autoridad de Huaccana reafirma su compromiso con la mejora de las condiciones de educabilidad de los estudiantes.',
			),
		),
		array(
			'slug'    => 'lanzamiento-del-proyecto-educativo-atipay',
			'title'   => 'UGEL Chincheros lanza el proyecto educativo ATIPAY',
			'date'    => '2024-08-05 09:48:00',
			'image'   => 'lanzamiento-proyecto-atipay.jpg',
			'alt'     => 'Auditorio de la I.E. José María Arguedas de Uripa durante el lanzamiento del proyecto ATIPAY',
			'excerpt' => 'ATIPAY es la estrategia de la UGEL Chincheros para mejorar la calidad educativa con docentes innovadores, directivos líderes, especialistas mentores y una sociedad comprometida, para formar estudiantes exitosos.',
			'source'  => 'https://www.gob.pe/institucion/ugelchincheros/noticias/998723-con-la-finalidad-de-lograr-estudiantes-exitosos-con-mentalidad-transformadora-realizan-lanzamiento-del-proyecto-educativo-atipay-desde-la-u',
			'content' => array(
				'Con la finalidad de lograr estudiantes exitosos con mentalidad transformadora, la UGEL Chincheros realizó el lanzamiento del proyecto educativo <strong>“Atipay”</strong>, una estrategia para la mejora de la calidad educativa. La actividad se desarrolló en el auditorio de la institución educativa José María Arguedas del distrito de Anco Huallo - Uripa.',
				'Participaron autoridades regionales, directores, jefes de Gestión Pedagógica y especialistas de educación de la DRE y de las UGEL de toda la región Apurímac, el alcalde provincial y los alcaldes distritales, además de directores, docentes, estudiantes, padres de familia, líderes sociales y la sociedad comprometida.',
				'## Una ceremonia con identidad andina',
				'La jornada empezó con la bienvenida a las autoridades con el bosque de gallardetes de los estudiantes de la I.E. José María Arguedas. En el auditorio, los docentes sabios andinos realizaron la ceremonia ritual de permiso a la Pachamama, con la presencia de la vicegobernadora de Apurímac, Lic. Marisol Valer Miranda; el director de la DRE Apurímac, Mag. Claudio Vilca Arapa; directores de las UGEL de la región y alcaldes de la provincia de Chincheros.',
				'## Qué propone ATIPAY',
				'El director de la UGEL Chincheros, Dr. Luis Moisés Sánchez Vergara, presentó el proyecto. Según explicó, la estrategia permitirá establecer un sistema educativo dinámico y participativo, en el que cada agente educativo desempeñe un papel esencial en la mejora continua de la calidad educativa, mediante:',
				'- programas de capacitación para docentes y directivos;|- redes de apoyo entre especialistas y docentes;|- la participación activa de la comunidad en la vida escolar;|- la adopción de nuevas tecnologías y métodos pedagógicos.',
				'El proyecto tiene como meta lograr <strong>docentes innovadores, directivos líderes, especialistas mentores, una sociedad comprometida e instituciones dinámicas</strong>, con la finalidad de formar estudiantes exitosos. En un segundo bloque se presentaron las ponencias de cada uno de sus seis componentes.',
			),
		),
		array(
			'slug'    => 'inauguracion-juegos-escolares-deportivos-y-paradeportivos-2024-huaccana',
			'title'   => 'Huaccana inaugura los Juegos Escolares Deportivos y Paradeportivos 2024, etapa UGEL',
			'date'    => '2024-08-05 09:40:00',
			'image'   => 'juegos-escolares-huaccana-2024.jpg',
			'alt'     => 'Estudiantes y delegaciones formados en la ceremonia de apertura de los Juegos Escolares en Huaccana',
			'excerpt' => 'Los estudiantes deportistas más destacados de la provincia de Chincheros se reunieron en la I.E. José María Flores de Huaccana, sede de la etapa UGEL de los Juegos Escolares Deportivos y Paradeportivos 2024.',
			'source'  => 'https://www.gob.pe/institucion/ugelchincheros/noticias/998706-fiesta-educativa-inauguracion-de-los-juegos-escolares-deportivos-y-paradeportivos-2024-en-el-distrito-de-huaccana',
			'content' => array(
				'Los estudiantes deportistas más destacados de toda la provincia de Chincheros se concentraron en la <strong>Institución Educativa José María Flores</strong> del distrito de Huaccana, sede de la etapa UGEL de los <strong>Juegos Escolares Deportivos y Paradeportivos 2024</strong>. Participaron el director de la UGEL, especialistas de educación, directores de las instituciones educativas, profesores, padres de familia y autoridades.',
				'## Una fiesta educativa',
				'Desde la UGEL Chincheros se reconoció a toda la comisión organizadora de la institución educativa por llevar adelante esta actividad, que tiene como objetivo fomentar el desarrollo de habilidades, promover la sana competencia y descubrir talentos deportivos entre los estudiantes.',
				'> «Como UGEL, nuestro objetivo es formar estudiantes deportistas exitosos».|— Prof. Luis Moisés Sánchez Vergara, director de la UGEL Chincheros',
				'Durante la ceremonia de apertura, el director de la UGEL Chincheros expresó su compromiso de apoyar a los estudiantes que representarán a la provincia en la etapa regional.',
				'Tras la ceremonia se realizó el desfile deportivo desde la plaza de armas del distrito hasta los escenarios deportivos de la institución educativa. La UGEL Chincheros trabajó para garantizar el desarrollo de la actividad, con la instalación del comité de justicia que resuelve las observaciones que se presenten.',
			),
		),
	);
}

/** Convierte los párrafos de los datos en bloques de WordPress. */
function ugelch_seed_blocks( $paragraphs, $source ) {
	$out = '';
	foreach ( $paragraphs as $p ) {
		if ( strpos( $p, '## ' ) === 0 ) {
			$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( substr( $p, 3 ) ) . "</h2>\n<!-- /wp:heading -->\n\n";
		} elseif ( strpos( $p, '- ' ) === 0 ) {
			$items = array_map( function ( $i ) { return '<li>' . wp_kses_post( ltrim( $i, '- ' ) ) . '</li>'; }, explode( '|', $p ) );
			$out  .= "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . implode( '', $items ) . "</ul>\n<!-- /wp:list -->\n\n";
		} elseif ( strpos( $p, '> ' ) === 0 ) {
			list( $q, $cite ) = array_pad( explode( '|', substr( $p, 2 ) ), 2, '' );
			$out .= "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>" . wp_kses_post( $q ) . '</p><cite>' . esc_html( $cite ) . "</cite></blockquote>\n<!-- /wp:quote -->\n\n";
		} else {
			$out .= "<!-- wp:paragraph -->\n<p>" . wp_kses_post( $p ) . "</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	$out .= "<!-- wp:paragraph {\"className\":\"news-source\"} -->\n<p class=\"news-source\">Fuente: <a href=\"" . esc_url( $source ) . "\">nota publicada por la UGEL Chincheros en gob.pe</a>.</p>\n<!-- /wp:paragraph -->";
	return $out;
}
