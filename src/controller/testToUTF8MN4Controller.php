<?php

namespace src\controller;

use DateTime;
use DateTimeZone;
use Exception;

class testToUTF8MN4Controller extends baseViewController
{
    /**
     * Process to set columns to UTF8MB4
     *
     *
     *  Descomentar linea 114, 148 y de 156 a 181
     *
     *
     *
     */
    public function doAction_1()
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/testToUTF8MN4_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '====================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

$txt = 'testing'.PHP_EOL; fwrite($this->myfile, $txt);

        // Configuración de la base de datos
        $database = 'altiraautomations';

        echo "====================================================<br />\n";
        echo "  ANÁLISIS DE VARCHAR PARA MIGRACIÓN A UTF8MB4<br />\n";
        echo "  Base de datos: $database<br />\n";
        echo "====================================================<br /><br />\n\n";

// Paso 1: Obtener todas las tablas
        $tablas = [];
        $result = $this->db->querySQL("SHOW TABLES");
//echo '<pre>'.print_r($result, TRUE).'</pre>';

        foreach ( $result as $key => $value )
        {
            $tablas[] = $value['Tables_in_'.$database];
        }
//echo '<pre>'.print_r($tablas, TRUE).'</pre>';

        echo "\nTotal de tablas encontradas: " . count($tablas) . "\n\n";
// Paso 2: Crear array con tablas y sus columnas VARCHAR
        $estructura = [];
        $problemas_encontrados = 0;
        $comandos_sql = [];

        foreach ( $tablas as $tabla ) {
            // Obtener columnas VARCHAR de cada tabla
            $sql = "
                    SELECT 
                        COLUMN_NAME,
                        CHARACTER_MAXIMUM_LENGTH,
                        COLUMN_KEY,
                        IS_NULLABLE,
                        COLUMN_DEFAULT,
                        COLUMN_TYPE
                    FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = '$database'
                        AND TABLE_NAME = '$tabla'
                        AND DATA_TYPE = 'varchar'
                    ORDER BY ORDINAL_POSITION
                    ";

            $result = $this->db->querySQL( $sql );
//echo '<pre>'.print_r($result, TRUE).'</pre>';

            if ( sizeof( $result ) > 0 )
            {
                $columnas = [];

                foreach ( $result as $key => $value )
                {
                    $columnas[] = $value;
                }
//                while ($col = $result->fetch_assoc()) {
//                    $columnas[] = $col;
//                }

                $estructura[$tabla] = $columnas;
            }
        }

//echo '<pre>'.print_r($estructura, TRUE).'</pre>';
        echo "📋 Tablas con columnas VARCHAR: " . count($estructura) . "\n\n";
        echo "====================================================\n\n";

// Paso 3: Analizar cada tabla y sus columnas
        foreach ( $estructura as $tabla => $columnas )
        {
            $tabla_tiene_problemas = false;
            $info_tabla = [];

            foreach ( $columnas as $col )
            {
                $nombre = $col['COLUMN_NAME'];
                $longitud = $col['CHARACTER_MAXIMUM_LENGTH'];
                $key = $col['COLUMN_KEY'];
                $nullable = $col['IS_NULLABLE'];
                $default = $col['COLUMN_DEFAULT'];

                // Calcular bytes con utf8mb4
                $bytes_utf8mb4 = $longitud * 4;

                // Determinar si hay problema
                $es_indexada = ($key != '');
                $tiene_problema = ($es_indexada && $bytes_utf8mb4 > 767);
                $posible_problema = ($es_indexada && $bytes_utf8mb4 > 500);

                //$tipo_indice = match($key){ 'PRI' => 'PRIMARY KEY', 'UNI' => 'UNIQUE', 'MUL' => 'INDEX', default => 'Sin índice'};

                $estado = 'OK';
                $accion = 'No requiere cambios';

                if ($tiene_problema)
                {
                    $estado = '⚠️ PROBLEMA';
                    $accion = "Reducir a VARCHAR(191)";
                    $problemas_encontrados++;
                    $tabla_tiene_problemas = true;

                    // Generar comando SQL
                    $sql_modify = "ALTER TABLE `$tabla` MODIFY `$nombre` VARCHAR(191)";
                    $sql_modify .= ($nullable == 'NO') ? ' NOT NULL' : ' NULL';
                    if ($default !== null) {
                        $sql_modify .= " DEFAULT '$default'";
                    }
                    $sql_modify .= ';';

                    $comandos_sql[] = [
                        'tabla' => $tabla,
                        'columna' => $nombre,
                        'comando' => $sql_modify
                    ];

                } elseif ($posible_problema) {
                    $estado = '⚠️ MONITOREAR';
                    $accion = 'Revisar si crece';
                }

                $info_tabla[] = [
                    'columna' => $nombre,
                    'longitud' => $longitud,
                    //'tipo_indice' => $tipo_indice,
                    'bytes' => $bytes_utf8mb4,
                    'estado' => $estado,
                    'accion' => $accion
                ];
            }

            // Mostrar solo tablas con problemas o índices
            /*
            if ( $tabla_tiene_problemas || count(array_filter($info_tabla, fn($i) => $i['tipo_indice'] != 'Sin índice')) > 0 )
            {
                echo "<br />📁 TABLA: $tabla<br />\n";
                echo str_repeat("-", 100) . "<br />\n";
                printf("%-30s %-10s %-15s %-10s %-20s %s<br />\n",
                    "COLUMNA", "LONGITUD", "TIPO ÍNDICE", "BYTES", "ESTADO", "ACCIÓN");

                foreach ($info_tabla as $info) {
                    // Solo mostrar columnas indexadas o con problemas
                    if ($info['tipo_indice'] != 'Sin índice' || $info['estado'] != 'OK') {
                        printf("%-30s %-10s %-15s %-10s %-20s %s<br />\n",
                            substr($info['columna'], 0, 28),
                            $info['longitud'],
                            $info['tipo_indice'],
                            $info['bytes'],
                            $info['estado'],
                            ($info['estado'] != 'OK' )? '<b>'.$info['accion'].'</b>' : $info['accion']
                        );
                    }
                }
                echo str_repeat("-", 100) . "<br />\n\n";

                echo "<br />\n\n";
            }
            */
    }

// Resumen final
        echo "<br /><br />";
        echo "====================================================<br />\n";
        echo "  RESUMEN DEL ANÁLISIS<br />\n";
        echo "====================================================<br />\n\n";

        echo "✅ Tablas analizadas: " . count($tablas) . "<br />\n";
        echo "📊 Tablas con VARCHAR: " . count($estructura) . "<br />\n";

        if ($problemas_encontrados > 0) {
            echo "❌ Problemas encontrados: $problemas_encontrados columnas<br /><br />\n\n";

            echo "====================================================<br />\n";
            echo "  COMANDOS SQL PARA CORREGIR PROBLEMAS<br />\n";
            echo "====================================================<br />\n\n";

            foreach ($comandos_sql as $cmd)
            {
                echo "-- Tabla: {$cmd['tabla']}, Columna: {$cmd['columna']}<br />\n";
                echo $cmd['comando'] . "<br />\n\n";
            }

            // Guardar comandos en archivo
            $archivo = 'fix_varchar_utf8mb4.sql';
            $contenido = "-- Comandos para corregir VARCHAR antes de migrar a UTF8MB4\n";
            $contenido .= "-- Base de datos: $database\n";
            $contenido .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
            $contenido .= "USE `$database`;\n\n";

            foreach ( $comandos_sql as $cmd )
            {
                $contenido .= "-- Tabla: {$cmd['tabla']}, Columna: {$cmd['columna']}\n";
                $contenido .= $cmd['comando'] . "\n\n";
            }

            file_put_contents($archivo, $contenido);
            echo "<br />💾 Comandos guardados en: $archivo<br />\n\n";

        } else {
            echo "✅ No se encontraron problemas<br />\n";
            echo "✅ Puedes proceder con la migración a UTF8MB4 de forma segura<br />\n\n";
        }

// Verificar ROW_FORMAT
        echo "====================================================<br />\n";
        echo "  VERIFICACIÓN DE ROW_FORMAT<br />\n";
        echo "====================================================<br /><br />\n\n";

        $sql_rowformat = "
                            SELECT 
                                TABLE_NAME,
                                ROW_FORMAT
                            FROM information_schema.TABLES
                            WHERE TABLE_SCHEMA = '$database'
                                AND TABLE_TYPE = 'BASE TABLE'
                                AND (ROW_FORMAT = 'Compact' OR ROW_FORMAT = 'Redundant')
                            ";

        $result = $this->db->querySQL($sql_rowformat);

        if ( sizeof( $result )  > 0) {
            echo "⚠️  Tablas que requieren cambio de ROW_FORMAT:<br />\n\n";

            $comandos_rowformat = [];
            while ($row = $result->fetch_assoc()) {
                $tabla = $row['TABLE_NAME'];
                $formato = $row['ROW_FORMAT'];
                echo "   - $tabla (formato actual: $formato)<br />\n";
                $comandos_rowformat[] = "ALTER TABLE `$tabla` ROW_FORMAT=DYNAMIC;";
            }

            echo "<br />\n-- Comandos para cambiar ROW_FORMAT:<br />\n\n";
            foreach ($comandos_rowformat as $cmd) {
                echo "$cmd<br />\n";
            }
            echo "\n";
        } else {
            echo "✅ Todas las tablas tienen ROW_FORMAT adecuado<br />\n\n";
        }


        echo "<br />====================================================<br />\n";
        echo "  ANÁLISIS COMPLETADO<br />\n";
        echo "====================================================<br />\n";

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Process to set db to UTF8MB4
     *
     */
    public function doAction_2()
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/testToUTF8MN4' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '====================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        /**
         * Migración completa de la base de datos a UTF8MB4
         * Base de datos: accedeme
         *
         * IMPORTANTE: Este script hará cambios permanentes en la base de datos
         * Asegúrate de tener un backup antes de ejecutar
         */
        $database = 'altiraautomations';

// Modo de ejecución
        $MODO_PRUEBA = true; // Cambiar a false para ejecutar realmente
        $CREAR_BACKUP = true; // Intentar crear backup automático

        echo "====================================================<br />\n";
        echo "  MIGRACIÓN A UTF8MB4<br />\n";
        echo "  Base de datos: $database<br />\n";
        echo "  Modo: " . ($MODO_PRUEBA ? "PRUEBA (solo simulación)" : "PRODUCCIÓN (ejecutará cambios)") . "<br />\n";
        echo "====================================================<br /><br />\n\n";

// ============================================
// PASO 0: CONVERSIÓN COLUMNAS
// ============================================
        echo "📋 PASO 0: Asegurate de haber cambiado las columnas con el procedimiento /test/to_utf8mb4_1<br />\n";
        /*
        ALTER TABLE `certification` MODIFY `domain_name` VARCHAR(191) NULL DEFAULT 'NULL';
        ALTER TABLE `domain` MODIFY `domain_name` VARCHAR(191) NULL DEFAULT 'NULL';
        ALTER TABLE `entity_contact` MODIFY `name` VARCHAR(191) NULL DEFAULT 'NULL';
        ALTER TABLE `entity_domain` MODIFY `domain_name` VARCHAR(191) NULL DEFAULT 'NULL';
        ALTER TABLE `pa11y_error` MODIFY `key` VARCHAR(191) NULL DEFAULT 'NULL';
        ALTER TABLE `user` MODIFY `email` VARCHAR(191) NULL DEFAULT 'NULL';
        ALTER TABLE `website` MODIFY `domain_name` VARCHAR(191) NULL DEFAULT 'NULL';
        ALTER TABLE `widget` MODIFY `domain_name` VARCHAR(191) NULL DEFAULT 'NULL';
        */
        echo str_repeat("-", 50) . "<br />\n";

// ============================================
// PASO 1: VERIFICACIÓN PREVIA
// ============================================
        echo "📋 PASO 1: Verificación previa<br />\n";
        echo str_repeat("-", 50) . "<br />\n";

// Verificar versión de MySQL/MariaDB
        $version = $this->db->querySQL("SELECT VERSION() as version");
//echo '<pre>'.print_r($version, TRUE).'</pre>';
        echo "✓ Versión: ".$version[0]['version']."<br />\n";
// Verificar charset actual de la BD
        $charset_actual = $this->db->querySQL("
                                                SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME
                                                FROM information_schema.SCHEMATA
                                                WHERE SCHEMA_NAME = '$database'
                                            ");
//echo '<pre>'.print_r($charset_actual, TRUE).'</pre>';
        $charset_actual = $charset_actual[0];
        echo "✓ Charset actual: {$charset_actual['DEFAULT_CHARACTER_SET_NAME']}\n";
        echo "✓ Collation actual: {$charset_actual['DEFAULT_COLLATION_NAME']}\n";

// Contar tablas
        $total_tablas = $this->db->querySQL("
                                            SELECT COUNT(*) as total 
                                            FROM information_schema.TABLES 
                                            WHERE TABLE_SCHEMA = '$database' AND TABLE_TYPE = 'BASE TABLE'
                                        ");
//echo '<pre>'.print_r($total_tablas, TRUE).'</pre>';
        $total_tablas = $total_tablas[0]['total'];

        echo "✓ Total de tablas: ".$total_tablas."<br />\n\n";

// ============================================
// PASO 2: BACKUP (opcional pero recomendado)
// ============================================
        if ( $CREAR_BACKUP && !$MODO_PRUEBA )
        {
            /*
            echo '<br /><br />';
            echo "💾 PASO 2: Creando backup<br />\n";
            echo str_repeat("-", 50) . "<br />\n";

            $backup_file = "backup_".$database."_" . date('Y-m-d_H-i-s') . ".sql";
            $backup_command = "mysqldump -h $host -u $user -p$pass $database > $backup_file 2>&1";

            echo "Ejecutando backup...\n";
            $output = shell_exec($backup_command);

            if (file_exists($backup_file) && filesize($backup_file) > 0) {
                $size = round(filesize($backup_file) / 1024 / 1024, 2);
                echo "✓ Backup creado: $backup_file ({$size}MB)\n\n";
            } else {
                echo "⚠️  No se pudo crear el backup automáticamente.\n";
                echo "   Por favor, crea un backup manual antes de continuar.\n";
                echo "   Comando: mysqldump -u $user -p $database > backup.sql\n\n";

                if (!$MODO_PRUEBA) {
                    echo "❌ DETENIENDO EJECUCIÓN por seguridad.\n";
                    exit(1);
                }
            }
            */
        } else {
            echo '<br /><br />';
            echo "⚠️  PASO 2: Backup desactivado o en modo prueba<br />\n";
            echo "   ASEGÚRATE DE TENER UN BACKUP MANUAL<br />\n\n";
        }

// ============================================
// PASO 3: OBTENER TODAS LAS TABLAS
// ============================================
        echo '<br /><br />';
        echo "📊 PASO 3: Obteniendo lista de tablas<br />\n";
        echo str_repeat("-", 50) . "<br />\n";

        $tablas = [];
        $result = $this->db->querySQL("SHOW TABLES");
//echo '<pre>'.print_r($result, TRUE).'</pre>';

        foreach ( $result as $key => $value)
        {
            $tablas[] = $value['Tables_in_'.$database];
        }
//        while ($row = $result->fetch_array()) {
//            $tablas[] = $row[0];
//        }

//echo '<pre>'.print_r($tablas, TRUE).'</pre>';
        echo "✓ Tablas a convertir: " . count($tablas) . "\n\n";

// ============================================
// PASO 4: CONVERTIR LA BASE DE DATOS
// ============================================
        echo '<br /><br />';
        echo "🔧 PASO 4: Convirtiendo base de datos<br />\n";
        echo str_repeat("-", 50) . "<br />\n";

        $sql_db = "ALTER DATABASE `$database` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci";

        if ( $MODO_PRUEBA )
        {
            echo "[SIMULACIÓN] $sql_db<br />\n";
        }
        else
        {
            if ( $this->db->querySQL($sql_db) )
            {
                echo "✓ Base de datos convertida a utf8mb4<br />\n";
            } else {
//                echo "❌ Error al convertir base de datos: " . $conn->error . "\n";
//echo '<pre>'.print_r($result, TRUE).'</pre>';
                echo "❌ Error al convertir base de datos: ".print_r($result, TRUE)."<br />\n";
            }
        }
        echo "<br />\n";

// ============================================
// PASO 5: CONVERTIR TODAS LAS TABLAS
// ============================================
        echo '<br /><br />';
        echo "🔄 PASO 5: Convirtiendo tablas individualmente<br />\n";
        echo str_repeat("-", 50) . "<br />\n";

        $exitosas = 0;
        $fallidas = 0;
        $errores = [];

        foreach ( $tablas as $index => $tabla )
        {
            $num = $index + 1;
            //echo "[".$num."/".$total_tablas." Convirtiendo: ".$tabla." ... ";

            $sql_tabla = "ALTER TABLE `$tabla` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

            if ( $MODO_PRUEBA )
            {
                //echo "SIMULADO ✓<br />\n";
                echo $sql_tabla.";<br />\n";
                $exitosas++;
            } else {
                if ( $result = $this->db->querySQL($sql_tabla) )
                {
                    echo "✓<br />\n";
                    $exitosas++;
                } else {
                    echo "✗<br />\n";
                    $fallidas++;
                    $errores[] = [
                        'tabla' => $tabla,
                        'error' => $result
                    ];
                }
            }
        }

        echo "<br />\n";

// ============================================
// PASO 6: VERIFICACIÓN POST-MIGRACIÓN
// ============================================
        echo '<br /><br />';
        echo "✅ PASO 6: Verificación post-migración<br />\n";
        echo str_repeat("-", 50) . "<br />\n";
            // Verificar charset de la BD
        $nuevo_charset = $this->db->querySQL("
                                            SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME
                                            FROM information_schema.SCHEMATA
                                            WHERE SCHEMA_NAME = '$database'
        ");
        $nuevo_charset = $nuevo_charset[0];
//echo '<pre>'.print_r($nuevo_charset, TRUE).'</pre>';

        echo "✓ Nuevo charset BD: {$nuevo_charset['DEFAULT_CHARACTER_SET_NAME']}<br />\n";
        echo "✓ Nuevo collation BD: {$nuevo_charset['DEFAULT_COLLATION_NAME']}<br />\n\n";

        // Verificar tablas convertidas
        $tablas_utf8mb4 = $this->db->querySQL("
                                                SELECT COUNT(*) as total
                                                FROM information_schema.TABLES T,
                                                     information_schema.COLLATION_CHARACTER_SET_APPLICABILITY CCSA
                                                WHERE CCSA.collation_name = T.table_collation
                                                  AND T.table_schema = '$database'
                                                  AND CCSA.character_set_name = 'utf8mb4'
        ");
//echo '<pre>'.print_r($tablas_utf8mb4, TRUE).'</pre>';
        $tablas_utf8mb4 = $tablas_utf8mb4[0]['total'];

        echo "✓ Tablas en utf8mb4: ".$tablas_utf8mb4." de ".$total_tablas."<br />\n\n";

// ============================================
// RESUMEN FINAL
// ============================================
        echo "====================================================<br />\n";
        echo "  RESUMEN DE LA MIGRACIÓN<br />\n";
        echo "====================================================<br />\n\n";

        if ($MODO_PRUEBA) {
            echo "ℹ️  MODO PRUEBA - No se realizaron cambios reales<br />\n\n";
        }

        echo "📊 Estadísticas:<br />\n";
        echo "   - Tablas procesadas exitosamente: $exitosas<br />\n";
        echo "   - Tablas con errores: $fallidas<br />\n\n";

        if (count($errores) > 0) {
            echo "❌ ERRORES ENCONTRADOS:<br />\n\n";
            foreach ($errores as $error) {
                echo "   Tabla: {$error['tabla']}<br />\n";
                echo "   Error: {$error['error']}<br />\n\n";
            }
        }

        if ($exitosas == $total_tablas && !$MODO_PRUEBA) {
            echo "✅ ¡MIGRACIÓN COMPLETADA EXITOSAMENTE!<br />\n\n";

            echo "📝 SIGUIENTE PASO IMPORTANTE:<br />\n";
            echo "   Actualiza TODOS tus archivos PHP de conexión con:<br />\n\n";
            echo "   \$conn->set_charset('utf8mb4');<br />\n";
            echo "   o\n";
            echo "   charset=utf8mb4 en el DSN de PDO<br />\n\n";

        } elseif ($MODO_PRUEBA) {
            echo "✅ Simulación completada sin errores<br />\n";
            echo "📝 Para ejecutar la migración real:<br />\n";
            echo "   1. Cambia \$MODO_PRUEBA = false; en el script<br />\n";
            echo "   2. Asegúrate de tener un backup<br />\n";
            echo "   3. Ejecuta el script nuevamente<br />\n\n";
        } else {
            echo "⚠️  Migración completada con algunos errores<br />\n";
            echo "   Revisa los errores arriba y corrige manualmente<br />\n\n";
        }

// Guardar log
        $log_file = "migracion_utf8mb4_" . date('Y-m-d_H-i-s') . ".log";
        ob_start();
        echo "Log de migración - " . date('Y-m-d H:i:s') . "\n\n";
        echo "Exitosas: $exitosas\n";
        echo "Fallidas: $fallidas\n\n";
        if (count($errores) > 0) {
            echo "Errores:\n";
            foreach ($errores as $error) {
                echo "- {$error['tabla']}: {$error['error']}\n";
            }
        }
        $log_content = ob_get_clean();
        file_put_contents($log_file, $log_content);
        echo "💾 Log guardado en: $log_file<br />\n";

        echo "\n====================================================<br />\n";
        echo "  PROCESO FINALIZADO<br />\n";
        echo "====================================================<br />\n";

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}