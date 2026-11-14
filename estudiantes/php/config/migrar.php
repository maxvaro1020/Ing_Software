<?php
// Autorreparación del esquema: crea las columnas que falten (idempotente).
// Así no hace falta importar actualizar_bd*.sql a mano; no borra ni sobrescribe datos.
function asegurarEsquema(PDO $pdo): void {
    try {
        $db = $pdo->query('SELECT DATABASE()')->fetchColumn();
        $st = $pdo->prepare('SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ?');
        $st->execute([$db]);
        $have = [];
        foreach ($st->fetchAll(PDO::FETCH_NUM) as [$t, $c]) $have[strtolower($t)][strtolower($c)] = true;

        // tabla => [columna => definición, 'fk' => sentencia opcional si la columna se acaba de crear]
        $need = [
            'grupos' => [
                'cupo'     => ['INT NOT NULL DEFAULT 15', null],
                'id_curso' => ['INT NULL', 'ALTER TABLE grupos ADD CONSTRAINT fk_grupo_curso FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE SET NULL'],
            ],
            'inscripciones' => [
                'id_grupo' => ['INT NULL', 'ALTER TABLE inscripciones ADD CONSTRAINT fk_insc_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE SET NULL'],
            ],
            'cursos' => [
                'lema'       => ['VARCHAR(255) NULL', null],
                'aprenderas' => ['TEXT NULL', null],
                'para_quien' => ['VARCHAR(255) NULL', null],
                'foto_url'   => ['VARCHAR(500) NULL', null],
            ],
            'usuarios' => [
                'telefono' => ['VARCHAR(30) NULL', null],
            ],
        ];
        foreach ($need as $tabla => $cols) {
            if (empty($have[$tabla])) continue;            // la tabla no existe: no se toca
            foreach ($cols as $col => [$def, $fk]) {
                if (!empty($have[$tabla][$col])) continue;
                $pdo->exec("ALTER TABLE $tabla ADD COLUMN $col $def");
                if ($fk) { try { $pdo->exec($fk); } catch (PDOException $e) { /* la llave foránea es opcional */ } }
            }
        }
    } catch (PDOException $e) {
        // Si falla la reparación, la página mostrará su propio error con más detalle.
    }
}
