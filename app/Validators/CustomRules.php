<?php

namespace App\Validators;

class CustomRules extends \CodeIgniter\Validation\Rules
{
    public function unique_sucursal(string $str, string $fields): bool
    {
        // Separar los elementos usando una coma y luego extraer el ID
        list($table, $field, $id) = array_merge(explode('.', $fields), [null]);

        // Verificar si el ID es nulo y asignar un valor predeterminado si es necesario
        $id = ($id !== null) ? $id : 0;

        $db = \Config\Database::connect();

        $builder = $db->table($table);

        $sucursal = $_SESSION['id_sucursal'];

        // Para la edición, excluye el registro actual mediante el ID
        $builder->where($field, $str)
            ->where('id_sucursal', $sucursal)
            ->where('id !=', $id);

        $row = $builder->get()->getRow();

        return $row === null;
    }
}
