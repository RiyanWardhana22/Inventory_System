<?php
function generateKode($prefix, $table, $field)
{
            global $conn;
            $sql = "SELECT $field FROM $table ORDER BY id DESC LIMIT 1";
            $result = $conn->query($sql);
            $last_kode = $result->fetch_assoc()[$field] ?? '';

            $number = 1;
            if ($last_kode) {
                        $last_number = (int) substr($last_kode, strlen($prefix) + 1);
                        $number = $last_number + 1;
            }

            return $prefix . '-' . date('YmdHis') . str_pad($number, 4, '0', STR_PAD_LEFT);
}

function checkAccess($menu_slug)
{
            global $conn, $user;

            // Super Admin memiliki akses penuh
            if ($user['role_title'] == 'Super Admin') return true;

            $sql = "SELECT rm.id FROM role_menus rm 
            JOIN menus m ON rm.menu_id = m.id 
            JOIN roles r ON rm.role_id = r.id 
            WHERE r.id = ? AND m.redirect LIKE ?";
            $stmt = $conn->prepare($sql);
            $like_param = "%$menu_slug%";
            $stmt->bind_param('is', $user['role_id'], $like_param);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows > 0;
}

function formatTanggal($date)
{
            return date('d F Y', strtotime($date));
}

function base_url($path = '')
{
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $project_path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            return $protocol . '://' . $host . $project_path . ltrim($path, '/');
}
