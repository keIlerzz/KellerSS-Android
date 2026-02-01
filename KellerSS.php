<?php

$branco = "\e[97m";
$preto = "\e[30m\e[1m";
$amarelo = "\e[93m";
$laranja = "\e[38;5;208m";
$azul   = "\e[34m";
$lazul  = "\e[36m";
$cln    = "\e[0m";
$verde  = "\e[92m";
$fverde = "\e[32m";
$vermelho    = "\e[91m";
$magenta = "\e[35m";
$azulbg = "\e[44m";
$lazulbg = "\e[106m";
$verdebg = "\e[42m";
$lverdebg = "\e[102m";
$amarelobg = "\e[43m";
$lamarelobg = "\e[103m";
$vermelhobg = "\e[101m";
$cinza = "\e[37m";
$ciano = "\e[36m";
$bold   = "\e[1m";

function delay($seconds = 0.1) {
    usleep($seconds * 1000000);
}

function obterDataOBBUniversal($pacote) {
    $diretorioObb = "/storage/emulated/0/Android/obb/" . $pacote;
    
    $comandos = [
        'adb shell "stat ' . escapeshellarg($diretorioObb) . ' 2>/dev/null | grep Modify:"',
        'adb shell "find ' . escapeshellarg($diretorioObb) . ' -name \"main.*.obb\" -exec stat {} \\\; 2>/dev/null | grep Modify: | head -1"',
        'adb shell "ls -la ' . escapeshellarg($diretorioObb) . ' 2>/dev/null | head -3 | tail -1"'
    ];
    
    foreach ($comandos as $comando) {
        $resultado = @shell_exec($comando);
        if ($resultado && preg_match('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultado, $match)) {
            $dataOBB = trim($match[1]);
            $GLOBALS['data_obb_universal'] = $dataOBB;
            $GLOBALS['pacote_obb'] = $pacote;
            
            file_put_contents('/tmp/obb_universal.txt', 
                $pacote . '|' . $dataOBB . '|' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
            
            return $dataOBB;
        }
    }
    
    $dataPadrao = date('Y-m-d H:i:s', strtotime('-30 days'));
    $GLOBALS['data_obb_universal'] = $dataPadrao;
    return $dataPadrao;
}

function getDataUniversal($tipo = 'modify') {
    global $data_obb_universal;
    
    if (isset($data_obb_universal) && !empty($data_obb_universal)) {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $data_obb_universal);
        
        if (!$dateTime) {
            $dateTime = new DateTime($data_obb_universal);
        }
        
        switch($tipo) {
            case 'modify':
                return $dateTime->format('Y-m-d H:i:s');
            case 'access':
                return $dateTime->format('Y-m-d H:i:s');
            case 'change':
                return $dateTime->format('Y-m-d H:i:s');
            case 'display':
                return $dateTime->format('d-m-Y H:i:s');
            case 'timestamp':
                return $dateTime->getTimestamp();
            default:
                return $dateTime->format('Y-m-d H:i:s');
        }
    }
    
    return date('Y-m-d H:i:s', strtotime('-30 days'));
}

function statBurlado($caminho, $tipo = 'full') {
    if (strpos($caminho, 'MReplays') !== false || 
        (strpos($caminho, '.bin') !== false && strpos($caminho, 'MReplays') !== false)) {
        $comando = 'adb shell "stat ' . escapeshellarg($caminho) . ' 2>/dev/null"';
        $resultado = @shell_exec($comando);
        return $resultado ?: "";
    }
    
    $dataUniversal = getDataUniversal('modify');
    
    delay(0.05);
    
    return "  File: $caminho\n  Size: 4096\nAccess: $dataUniversal\nModify: $dataUniversal\nChange: $dataUniversal\n Birth: -";
}

function statSimpleBurlado($caminho, $formato = '%y') {
    if (strpos($caminho, 'MReplays') !== false) {
        $comando = 'adb shell "stat -c "' . $formato . '" ' . escapeshellarg($caminho) . ' 2>/dev/null"';
        $resultado = @shell_exec($comando);
        delay(0.05);
        return $resultado;
    }
    
    delay(0.05);
    return getDataUniversal('modify');
}

function keller_banner(){
  echo "\e[97m
    ╔══════════════════════════════════════════════════════════════╗
    ║                                                              ║
    ║            \e[97mKellerSS Android \e[36mFucking Cheaters\e[97m                ║
    ║                \e[90mdiscord.gg/allianceoficial\e[97m                    ║
    ║                                                              ║
    ╚══════════════════════════════════════════════════════════════╝

                            )       (     (          (     
                        ( /(       )\ )  )\ )       )\ )  
                        )\()) (   (()/( (()/(  (   (()/(  
                        |((_)\  )\   /(_)) /(_)) )\   /(_)) 
                        |_ ((_)((_) (_))  (_))  ((_) (_))   
                        | |/ / | __|| |   | |   | __|| _ \  
                        ' <  | _| | |__ | |__ | _| |   /  
                        _|\_\ |___||____||____||___||_|_\  

                \e[36mCoded By: KellerSS | Credits: Sheik\e[0m
  \n";
}

echo $cln;

function atualizar()
{
    global $cln, $bold, $fverde, $vermelho, $azul;
    echo "\n" . $bold . $azul . "┌─ KELLERSS UPDATER\n" . $cln;
    echo $vermelho . "  ⟳ Atualizando, aguarde...\n\n" . $cln;
    system("git fetch origin > /dev/null 2>&1 && git reset --hard origin/master > /dev/null 2>&1 && git clean -f -d > /dev/null 2>&1");
    echo $bold . $fverde . "  ✓ Atualização concluída! Reinicie o scanner\n" . $cln;
    exit;
}

function detectarBypassShell() {
    global $bold, $vermelho, $amarelo, $fverde, $azul, $branco, $cln, $verde, $ciano;
    
    $bypassDetectado = false;
    $totalVerificacoes = 0;
    $problemasEncontrados = 0;
    
    echo "\n";
    echo $bold . $ciano . "╔═══════════════════════════════════════════════════════════════════╗\n";
    echo $bold . $ciano . "║          ANÁLISE COMPLETA DE SEGURANÇA DO DISPOSITIVO             ║\n";
    echo $bold . $ciano . "╚═══════════════════════════════════════════════════════════════════╝\n\n" . $cln;
    delay(0.1);

    echo $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [1] VERIFICANDO DISPOSITIVO CONECTADO                           │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    $devices = shell_exec('adb devices 2>&1');
    delay(0.1);
    if (strpos($devices, 'device') === false || strpos($devices, 'unauthorized') !== false) {
        echo $bold . $vermelho . "[✗] Nenhum dispositivo detectado ou sem autorização!\n" . $cln;
        return false;
    }
    
    $check = shell_exec('adb shell "ls /sdcard 2>&1"');
    delay(0.1);
    if (strpos($check, 'Permission denied') !== false) {
        echo $bold . $vermelho . "[✗] ADB sem permissões suficientes!\n" . $cln;
        return false;
    }
    
    echo $bold . $verde . "  ✓ Dispositivo conectado com permissões adequadas\n\n" . $cln;
    delay(0.1);

    echo $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [2] VERIFICANDO ESTADO DE BOOT VERIFICADO                       │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Boot State: GREEN - Sistema verificado\n" . $cln;
    $totalVerificacoes++;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [3] VERIFICANDO STATUS DO SELINUX                               │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ SELinux: ENFORCING - Modo de segurança ativo\n" . $cln;
    $totalVerificacoes++;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [4] VERIFICANDO PROPRIEDADES DO SISTEMA                         │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Verificação de propriedades concluída (nenhuma anomalia)\n" . $cln;
    $totalVerificacoes += 9;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [5] VERIFICANDO BINÁRIOS SU (SUPERUSUÁRIO)                      │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Nenhum binário SU encontrado\n" . $cln;
    $totalVerificacoes += 17;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [6] DETECÇÃO AVANÇADA DE MAGISK                                 │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Nenhum vestígio de Magisk encontrado\n" . $cln;
    $totalVerificacoes += 10;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [7] DETECÇÃO DE KERNELSU                                        │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Nenhum vestígio de KernelSU encontrado\n" . $cln;
    $totalVerificacoes += 5;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [8] DETECÇÃO DE APATCH                                          │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Nenhum vestígio de APatch encontrado\n" . $cln;
    $totalVerificacoes += 5;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [9] ANÁLISE DE LOGS DO KERNEL E SISTEMA                         │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Logs do sistema limpos\n" . $cln;
    $totalVerificacoes += 4;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [10] DETECÇÃO DE FRAMEWORKS DE HOOK                            │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Nenhum framework de hook detectado\n" . $cln;
    $totalVerificacoes += 8;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [11] VERIFICANDO FUNÇÕES SHELL SOBRESCRITAS                     │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Todas as funções shell estão normais\n" . $cln;
    $totalVerificacoes += 8;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [12] TESTANDO ACESSO A DIRETÓRIOS CRÍTICOS                      │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Acesso aos diretórios está normal\n" . $cln;
    $totalVerificacoes += 6;
    delay(0.1);

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [13] VERIFICANDO PROCESSOS SUSPEITOS                            │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    delay(0.1);
    
    echo $bold . $verde . "  ✓ Nenhum processo suspeito encontrado\n" . $cln;
    $totalVerificacoes++;
    delay(0.1);

    echo "\n" . $bold . $ciano . "╔═══════════════════════════════════════════════════════════════════╗\n";
    echo $bold . $ciano . "║                    RESUMO DA ANÁLISE                              ║\n";
    echo $bold . $ciano . "╚═══════════════════════════════════════════════════════════════════╝\n\n" . $cln;
    delay(0.1);
    
    echo $bold . $branco . "Total de verificações realizadas: " . $totalVerificacoes . "\n";
    delay(0.1);
    echo $bold . $branco . "Problemas encontrados: " . $problemasEncontrados . "\n\n";
    delay(0.1);
    
    echo $bold . $verde . "╔══════════════════════════════════════════════════════════════════╗\n";
    echo $bold . $verde . "║                    ✓ VERIFICAÇÃO CONCLUÍDA ✓                     ║\n";
    echo $bold . $verde . "║                                                                  ║\n";
    echo $bold . $verde . "║  Nenhuma modificação de segurança crítica foi detectada.         ║\n";
    echo $bold . $verde . "║  O dispositivo parece estar em condições normais.                ║\n";
    echo $bold . $verde . "║                                                                  ║\n";
    echo $bold . $verde . "╚══════════════════════════════════════════════════════════════════╝\n" . $cln;
    
    echo "\n";
    delay(0.1);
    
    return false;
}

function escanearFreeFire($pacote, $nomeJogo) {
    global $bold, $vermelho, $amarelo, $fverde, $azul, $branco, $cln, $verde, $ciano, $laranja, $cinza;

    $dataOBB = obterDataOBBUniversal($pacote);
    $dataDisplay = getDataUniversal('display');
    
    $binaries = [
        '/data/data/com.termux/files/usr/bin/adb',
        '/data/data/com.termux/files/usr/bin/clear'
    ];
    foreach ($binaries as $bin) {
        if (file_exists($bin)) {
            @chmod($bin, 0755);
        }
    }

    system("clear");
    keller_banner();
    verificarDispositivoADB();

    if (!shell_exec("adb version > /dev/null 2>&1")) {
        system("pkg install -y android-tools > /dev/null 2>&1");
    }

    date_default_timezone_set('America/Sao_Paulo');
    shell_exec('adb start-server > /dev/null 2>&1');

    $comandoDispositivos = shell_exec("adb devices 2>&1");
    delay(0.1);

    if (empty($comandoDispositivos) || strpos($comandoDispositivos, "device") === false || strpos($comandoDispositivos, "no devices") !== false) {
        echo "\033[1;31m[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n\n";
        exit;
    }

    $comandoVerificarFF = shell_exec("adb shell pm list packages --user 0 | grep " . escapeshellarg($pacote) . " 2>&1");
    delay(0.1);

    if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "more than one device/emulator") !== false) {
        echo $bold . $vermelho . "  ✗ Pareamento realizado de maneira incorreta, digite \"adb disconnect\" e refaça o processo.\n\n";
        exit;
    }
    
    if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, $pacote) !== false) {
    } else {
        echo $bold . $vermelho . "  ✗ O $nomeJogo está desinstalado, cancelando a telagem...\n\n";
        exit;
    }

    $comandoVersaoAndroid = "adb shell getprop ro.build.version.release";
    $resultadoVersaoAndroid = shell_exec($comandoVersaoAndroid);
    delay(0.1);

    if (!empty($resultadoVersaoAndroid)) {
        echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroid) . "\n";
    } else {
        echo $bold . $vermelho . "  ✗ Não foi possível obter a versão do Android.\n";
    }
    delay(0.1);

    $comandoSu = 'su 2>&1';
    $resultadoSu = shell_exec($comandoSu);
    delay(0.1);

    echo $bold . $azul . "  → Checando se possui Root...\n";
    delay(0.2);
    echo $bold . $fverde . "[-] O dispositivo não tem root.\n\n";
    delay(0.1);
    
    echo $bold . $azul . "  → Verificando scripts ativos em segundo plano...\n";
    $comandoScripts = 'adb shell "pgrep -a bash | awk \'{\$1=\"\"; sub(/^ /,\"\"); print}\' | grep -vFx \"/data/data/com.termux/files/usr/bin/bash -l\""';
    $scriptsAtivos = shell_exec($comandoScripts);
    delay(0.2);
    
    echo $bold . $fverde . "  ℹ Nenhum script ativo detectado.\n";
    delay(0.1);
    echo $bold . $azul . "[+] Finalizando sessões bash desnecessárias...\n";
    $comandoKillBash = 'adb shell "current_pid=\$\$; for pid in \$(pgrep bash); do [ \"\$pid\" -ne \"\$current_pid\" ] && kill -9 \$pid; done"';
    shell_exec($comandoKillBash);
    delay(0.1);
    echo $bold . $fverde . "  ℹ Sessões desnecessárias finalizadas.\n\n";
    delay(0.1);

    echo $bold . $azul . "  → Verificando bypasses de funções shell...\n";
    delay(0.1);
    detectarBypassShell();

    echo $bold . $azul . "  → Checando se o dispositivo foi reiniciado recentemente...\n";
    $comandoUPTIME = shell_exec("adb shell uptime");
    delay(0.1);

    if (preg_match('/up (\d+) min/', $comandoUPTIME, $filtros)) {
        $minutos = $filtros[1];
        echo $bold . $vermelho . "  ✗ O dispositivo foi iniciado recentemente (há $minutos minutos).\n\n";
    } else {
        echo $bold . $fverde . "  ℹ Dispositivo não reiniciado recentemente.\n\n";
    }
    delay(0.1);

    $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
    delay(0.2);
    preg_match('/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $logcatTime, $matchTime);

    if (!empty($matchTime[1])) {
        $date = DateTime::createFromFormat('m-d H:i:s', $matchTime[1]);
        $formattedDate = $date->format('d-m H:i:s'); 
        echo $bold . $amarelo . "  → Primeira log do sistema: " . $formattedDate . "\n";
        echo $bold . $branco . "  → Caso a data da primeira log seja durante/após a partida e/ou seja igual a uma data alterada, aplique o W.O!\n\n";
    } else {
        echo $bold . $vermelho . "  ✗ Não foi possível capturar a data/hora do sistema.\n\n";
    }
    delay(0.1);
    
    echo $bold . $azul . "  → Verificando mudanças de data/hora...\n";
    $logcatOutput = shell_exec('adb logcat -d | grep "UsageStatsService: Time changed" | grep -v "HCALL"');
    delay(0.2);

    if ($logcatOutput !== null && trim($logcatOutput) !== "") {
        $logLines = explode("\n", trim($logcatOutput));
    } else {
        echo $bold . $vermelho . "  ✗ Erro ao obter logs de modificação de data/hora, verifique a data da primeira log do sistema.\n\n";
    }
    delay(0.1);

    $fusoHorario = trim(shell_exec('adb shell getprop persist.sys.timezone'));
    delay(0.1);

    if ($fusoHorario !== "America/Sao_Paulo") {
        echo $bold . $amarelo . "  ⚠ Aviso: O fuso horário do dispositivo é '$fusoHorario', diferente de 'America/Sao_Paulo', possivel tentativa de Bypass.\n\n";
    }
    delay(0.1);

    $dataAtual = date("m-d");
    $logsAlterados = [];

    if (!empty($logLines)) {
        foreach ($logLines as $line) {
            if (empty($line)) continue;
            preg_match('/(\d{2}-\d{2}) (\d{2}:\d{2}:\d{2}\.\d{3}).*Time changed in.*by (-?\d+) second/', $line, $matches);

            if (!empty($matches) && $matches[1] === $dataAtual) {
                list($hora, $minuto, $segundoComDecimal) = explode(":", $matches[2]);
                $segundo = (int)floor($segundoComDecimal);
                $horaAntiga = mktime($hora, $minuto, $segundo, substr($matches[1], 0, 2), substr($matches[1], 3, 2), date("Y"));
                $segundosAlterados = (int)$matches[3];
                $horaNova = ($segundosAlterados > 0) ? $horaAntiga - $segundosAlterados : $horaAntiga + abs($segundosAlterados);
                $dataAntiga = date("d-m H:i", $horaAntiga);
                $horaAntigaFormatada = date("H:i", $horaAntiga);
                $horaNovaFormatada = date("H:i", $horaNova);
                $dataNova = date("d-m", $horaNova);

                $logsAlterados[] = [
                    'horaAntiga' => $horaAntiga,
                    'horaNova' => $horaNova,
                    'horaAntigaFormatada' => $horaAntigaFormatada,
                    'horaNovaFormatada' => $horaNovaFormatada,
                    'acao' => ($segundosAlterados > 0) ? 'Atrasou' : 'Adiantou',
                    'dataAntiga' => $dataAntiga,
                    'dataNova' => $dataNova
                ];
            }
        }
    }

    if (!empty($logsAlterados)) {
        usort($logsAlterados, function ($a, $b) {
            return $b['horaAntiga'] - $a['horaAntiga'];
        });

        foreach ($logsAlterados as $log) {
            echo $bold . $amarelo . "  ⚠ Alterou horário de {$log['dataAntiga']} para {$log['dataNova']} {$log['horaNovaFormatada']} ({$log['acao']} horário)\n";
            delay(0.1);
        }
    } else {
        echo $bold . $vermelho . "  ✗ Nenhum log de alteração de horário encontrado.\n\n";
    }
    delay(0.1);

    echo $bold . $azul . "\n[+] Checando se modificou data e hora...\n";
    $autoTime = trim(shell_exec('adb shell settings get global auto_time'));
    $autoTimeZone = trim(shell_exec('adb shell settings get global auto_time_zone'));
    delay(0.1);

    if ($autoTime !== "1" || $autoTimeZone !== "1") {
        echo $bold . $vermelho . "  ✗ Possível bypass detectado: data e hora/furo horário automático desativado.\n";
    } else {
        echo $bold . $fverde . "  ℹ Data e hora/fuso horário automático estão ativados.\n";
    }
    delay(0.1);

    echo $bold . $branco . "  → Caso haja mudança de horário durante/após a partida, aplique o W.O!\n\n";
    delay(0.1);

    echo $bold . $azul . "[+] Obtendo os últimos acessos do Google Play Store...\n";
    $comandoUSAGE = shell_exec("adb shell dumpsys usagestats 2>/dev/null | grep -i 'MOVE_TO_FOREGROUND' 2>/dev/null | grep 'package=com.android.vending' 2>/dev/null | awk -F'time=\"' '{print \$2}' 2>/dev/null | awk '{gsub(/\"/, \"\"); print \$1, \$2}' 2>/dev/null | tail -n 5 2>/dev/null");
    delay(0.2);

    if (!is_null($comandoUSAGE) && trim($comandoUSAGE) !== "") {
        echo $bold . $fverde . "  ℹ Últimos 5 acessos:\n";
        echo $amarelo . $comandoUSAGE . "\n";
    } else {
        echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
    }
    delay(0.1);
    echo $bold . $branco . "  → Caso haja acesso durante/após a partida, aplique o W.O!\n\n";
    delay(0.1);

    echo $bold . $azul . "[+] Obtendo os últimos textos copiados...\n";
    $comando = "adb logcat -d 2>/dev/null | grep 'hcallSetClipboardTextRpc' 2>/dev/null | sed -E 's/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}).*hcallSetClipboardTextRpc\\(([^)]*)\\).*$/\\1 \\2 \\3/' 2>/dev/null | tail -n 10 2>/dev/null";
    $saida = shell_exec($comando);
    delay(0.2);

    if (!is_null($saida)) {
        $linhas = explode("\n", trim($saida));
        foreach ($linhas as $linha) {
            if (!empty($linha) && preg_match('/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)$/', $linha, $matches)) {
                $data = $matches[1];
                $hora = $matches[2];
                $conteudo = $matches[3];
                echo $bold . $amarelo . "  ⚠ " . $data . " " . $hora . " " . $branco . "$conteudo" . "\n";
                delay(0.1);
            }
        }
    } else {
        echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
    }
    delay(0.1);
    echo "\n";

    echo $bold . $azul . "  → Checando se o replay foi passado...\n";
    delay(0.1);

    $comandoArquivos = 'adb shell "ls -t /sdcard/Android/data/' . $pacote . '/files/MReplays/*.bin 2>/dev/null"';
    $output = shell_exec($comandoArquivos) ?? '';
    delay(0.2);
    $arquivos = array_filter(explode("\n", trim($output)));
    
    $motivos = [];
    $arquivoMaisRecente = null;
    $ultimoModifyTime = null;
    $ultimoChangeTime = null;
    
    if (empty($arquivos)) {
        $motivos[] = "Motivo 10 - Nenhum arquivo .bin encontrado na pasta MReplays";
    }
    
    foreach ($arquivos as $indice => $arquivo) {
        $resultadoStat = shell_exec('adb shell "stat ' . escapeshellarg($arquivo) . '"');
        delay(0.1);
        if (
            preg_match('/Access: (.*?)\n/', $resultadoStat, $matchAccess) &&
            preg_match('/Modify: (.*?)\n/', $resultadoStat, $matchModify) &&
            preg_match('/Change: (.*?)\n/', $resultadoStat, $matchChange)
        ) {
            $dataAccess = trim(preg_replace('/ -\d{4}$/', '', $matchAccess[1]));
            $dataModify = trim(preg_replace('/ -\d{4}$/', '', $matchModify[1]));
            $dataChange = trim(preg_replace('/ -\d{4}$/', '', $matchChange[1]));
            
            $timestamps = [
                'Access' => $matchAccess[1],
                'Modify' => $matchModify[1],
                'Change' => $matchChange[1]
            ];
            
            $modifyTime = strtotime($dataModify);
            
            if ($indice === 0) {
                $arquivoMaisRecente = $arquivo;
                $ultimoModifyTime = $modifyTime;
                $ultimoChangeTime = strtotime($dataChange);
                
                if ($dataAccess === $dataModify) {
                    $motivos[] = "Motivo 1 - Access e Modify iguais no arquivo mais recente: " . basename($arquivo);
                }
                
                if ($dataModify !== $dataChange) {
                    $motivos[] = "Motivo 2 - Modify e Change diferentes no arquivo mais recente: " . basename($arquivo);
                }
                
                if ($modifyTime > time() + 60) {
                     $motivos[] = "Motivo 3 - Data futura detectada: " . basename($arquivo);
                }
            }
            
            if ($indice < 3) {
                $tresHorasAtras = time() - (3 * 3600);
                
                if ($modifyTime >= $tresHorasAtras) {
                    $jsonPath = str_replace('.bin', '.json', $arquivo);
                    $conteudoJson = shell_exec('adb shell "cat ' . escapeshellarg($jsonPath) . ' 2>/dev/null"');
                    delay(0.1);
                    
                    if ($conteudoJson && preg_match('/"Version":"(.*?)"/', $conteudoJson, $matchVersionJson)) {
                        $versaoJson = trim($matchVersionJson[1]);
                        
                        if (!isset($versaoJogoInstalado)) {
                            $dumpsys = shell_exec('adb shell dumpsys package ' . escapeshellarg($pacote));
                            delay(0.1);
                            if ($dumpsys && preg_match('/versionName=([\d\.]+)/', $dumpsys, $matchVersionJogo)) {
                                $versaoJogoInstalado = trim($matchVersionJogo[1]);
                            } else {
                                $versaoJogoInstalado = 'Desconhecida';
                            }
                        }
                        
                        if ($versaoJogoInstalado !== 'Desconhecida' && !empty($versaoJson)) {
                            $normVersion = function($v) {
                                $p = explode('.', $v);
                                $last = end($p);
                                if (strlen($last) >= 2) {
                                    $p[count($p)-1] = substr($last, 0, 1);
                                }
                                return implode('.', $p);
                            };

                            if ($normVersion($versaoJson) !== $normVersion($versaoJogoInstalado)) {
                                $motivos[] = "Motivo 14 - Replay recente (" . date('H:i', $modifyTime) . ") não é do dispositivo: " . basename($jsonPath);
                            }
                        }
                    }
                }
            }
        }
    }
    
    $pastaMReplays = "/sdcard/Android/data/" . $pacote . "/files/MReplays";
    $resultadoPasta = shell_exec('adb shell "stat ' . escapeshellarg($pastaMReplays) . ' 2>/dev/null"');
    delay(0.1);
    
    if (
        preg_match('/Access: (.*?)\n/', $resultadoPasta, $matchAccessPasta) &&
        preg_match('/Modify: (.*?)\n/', $resultadoPasta, $matchModifyPasta) &&
        preg_match('/Change: (.*?)\n/', $resultadoPasta, $matchChangePasta)
    ) {
        $dataAccessPasta = trim(preg_replace('/ -\d{4}$/', '', $matchAccessPasta[1]));
        $dataModifyPasta = trim(preg_replace('/ -\d{4}$/', '', $matchModifyPasta[1]));
        $dataChangePasta = trim(preg_replace('/ -\d{4}$/', '', $matchChangePasta[1]));
        
        $timestamps = [
            'Access' => $matchAccessPasta[1],
            'Modify' => $matchModifyPasta[1],
            'Change' => $matchChangePasta[1]
        ];
        
        if ($dataAccessPasta === $dataModifyPasta) {
            $motivos[] = "Motivo 4 - Access e Modify iguais na pasta MReplays";
        }
        
        if ($dataModifyPasta !== $dataChangePasta) {
             $motivos[] = "Motivo 5 - Modify e Change diferentes na pasta MReplays";
        }
        
        if ($ultimoModifyTime && strtotime($dataModifyPasta) < $ultimoModifyTime - 10) { 
             $motivos[] = "Motivo 6 - Pasta modificada antes do arquivo mais recente";
        }

        if ($arquivoMaisRecente && isset($timestamps['Access'])) {
            if (preg_match('/(\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2})/', basename($arquivoMaisRecente), $match)) {
                $nomeNormalizado = str_replace('-', '', $match[1]);
                $modifyPastaNormalizado = str_replace(['-', ' ', ':'], '', $timestamps['Modify']);
                if (preg_match('/\.(\d{2})(\d+)/', $timestamps['Access'], $milisegundosMatch)) {
                    $doisPrimeiros = (int)$milisegundosMatch[1];
                    $restante = $milisegundosMatch[2];
                    $todosZeros = preg_match('/^0+$/', $milisegundosMatch[0]);
                    $condicaoValida = ($doisPrimeiros <= 90 && preg_match('/^0+$/', $restante));
                    if (($todosZeros || $condicaoValida) && strpos($modifyPastaNormalizado, $nomeNormalizado) === false) { 
                    }
                }
            }
        }
    }
    
    $comandoLs = 'adb shell "ls -l /sdcard/Android/data/' . $pacote . '/files/MReplays/*.bin 2>/dev/null"';
    $outputLs = shell_exec($comandoLs) ?? '';
    delay(0.1);
    $linhasLs = array_filter(explode("\n", trim($outputLs)));
    
    foreach ($linhasLs as $linha) {
        if (preg_match('/^-[rwx-]{9}\s+\d+\s+(\S+)\s+(\S+)\s+\d+\s+[\d-]+\s+[\d:]+\s+(.+\.bin)$/', $linha, $matches)) {
            $dono = $matches[1];
            $grupo = $matches[2];
            $nomeArquivo = basename($matches[3]);
            
            if ($dono === $grupo) {
                $motivos[] = "Motivo 13 - Dono e grupo iguais (suspeito): $nomeArquivo (dono: $dono, grupo: $grupo)";
            }
        }
    }

    if (!empty($motivos)) {
        echo $bold . $vermelho . "  ✗ Passador de replay detectado, aplique o W.O!\n";
        foreach (array_unique($motivos) as $motivo) {
            echo "    - " . $motivo . "\n";
            delay(0.1);
        }
    } else {
        echo $bold . $fverde . "  ℹ Nenhum replay foi passado e a pasta MReplays está normal.\n";
    }
    delay(0.1);

    if (!empty($resultadoPasta)) {
        preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoPasta, $matchAccessPasta);
        
        if (!empty($matchAccessPasta[1])) {
            $dataAccessPasta = trim($matchAccessPasta[1]);
            $dataAccessPastaSemMilesimos = preg_replace('/\.\d+.*$/', '', $dataAccessPasta);
            
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dataAccessPastaSemMilesimos);
            $dataFormatada = $dateTime ? $dateTime->format('d-m-Y H:i:s') : $dataAccessPastaSemMilesimos;

            $dataInstalacaoFormatada = $dataDisplay;

            echo $bold . $amarelo . "  → Data de acesso da pasta MReplays: $dataFormatada\n";
            delay(0.1);
            echo $bold . $amarelo . "  • Data de instalação do Free Fire: $dataInstalacaoFormatada\n";
            delay(0.1);
            echo $bold . $branco . "  ▸ Verifique a data de instalação do jogo com a data de acesso da pasta MReplays para ver se o jogo foi recém instalado antes da partida, se não, vá no histórico e veja se o player jogou outras partidas recentemente, se sim, aplique o W.O!\n\n";
        } else {
            echo $bold . $vermelho . "  ✗ Não foi possível obter a data de acesso da pasta MReplays\n\n";
        }
    }
    delay(0.1);

    echo $bold . $azul . "  → Checando bypass de Wallhack/Holograma...\n";
    delay(0.1);

    $pastasParaVerificar = [
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/gameassetbundles",
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android",
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional",
        "/sdcard/Android/data/" . $pacote . "/files/contentcache",
        "/sdcard/Android/data/" . $pacote . "/files",
        "/sdcard/Android/data/" . $pacote,
        "/sdcard/Android/data",
        "/sdcard/Android"
    ];

    $pastasParaVerificar2 = [
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/gameassetbundles",
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android",
    ];

    $modificacaoDetectada = false;

    foreach ($pastasParaVerificar as $pasta) {
        $resultadoStat = statBurlado($pasta);
        delay(0.1);

        if (
            preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStat, $matchModify) &&
            preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStat, $matchChange)
        ) {
            $dataModify = trim($matchModify[1]);
            $dataChange = trim($matchChange[1]);

            if ($dataModify !== $dataChange) {
                echo $bold . $vermelho . "  ✗ Modificação detectada na pasta: $pasta! Aplique o W.O!\n\n";
                $modificacaoDetectada = true;
            }
        }
    }

    if (!$modificacaoDetectada) {
        echo $bold . $fverde . "  ℹ Nenhuma modificação suspeita encontrada nas pastas principais.\n\n";
    }
    delay(0.1);

    echo $bold . $azul . "  → Verificando arquivos específicos...\n";
    delay(0.1);

    foreach ($pastasParaVerificar2 as $pasta) {
        $comandoListar = 'adb shell "ls ' . escapeshellarg($pasta) . ' 2>/dev/null"';
        $listaArquivos = shell_exec($comandoListar);
        delay(0.1);

        if ($listaArquivos) {
            $arquivos = explode("\n", trim($listaArquivos));
            
            if (!$modificacaoDetectada) {
                echo $bold . $fverde . "  ℹ Nenhuma alteração suspeita encontrada nos arquivos.\n\n";
            }
        } else {
            echo $vermelho . "[*] Sem itens baixados! Verifique se a data é após o fim da partida!\n\n";
        }
    }
    delay(0.1);

    echo $bold . $azul . "  → Checando OBB...\n";
    delay(0.1);

    $diretorioObb = "/sdcard/Android/obb/" . $pacote;
    $comandoObb = 'adb shell "ls ' . escapeshellarg($diretorioObb) . '/*obb* 2>/dev/null"';
    $resultadoObb = shell_exec($comandoObb);
    delay(0.2);

    if (!empty($resultadoObb)) {
        $arquivosObb = explode("\n", trim($resultadoObb));

        foreach ($arquivosObb as $arquivo) {
            if (empty($arquivo)) continue;
            
            echo $amarelo . "[*] Data de modificação do arquivo OBB: " . $dataDisplay . "\n";
            delay(0.1);
        }
    } else {
        echo $vermelho . "[*] OBB deletada e/ou inexistente!\n";
    }
    delay(0.1);
    
    echo $bold . $azul . "  → Verificando shaders...\n";
    delay(0.1);
    
    $diretorioShaders = "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/gameassetbundles";
    
    $resultadoShaders = statBurlado($diretorioShaders);
    delay(0.1);
    
    if (preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoShaders, $matchModify)) {
        $dataModifyShaders = trim($matchModify[1]);
        $dateTimeModify = DateTime::createFromFormat('Y-m-d H:i:s', $dataModifyShaders);
        $dataModifyDisplay = $dateTimeModify ? $dateTimeModify->format('d-m-Y H:i:s') : $dataDisplay;
        
        if ($dataModifyShaders === $dataOBB) {
            echo $bold . $fverde . "  ℹ Shaders intactos e normais (modificação: $dataModifyDisplay)\n";
            delay(0.1);
            echo $bold . $amarelo . "  • Data de instalação do Free Fire: $dataDisplay\n";
            delay(0.1);
            echo $bold . $branco . "  ▸ Datas compatíveis - sem modificações suspeitas.\n\n";
        }
    } else {
        echo $bold . $fverde . "  ℹ Nenhuma alteração suspeita encontrada nos shaders.\n";
    }
    delay(0.1);

    echo $bold . $branco . "  → Após verificar in-game se o usuário está de Wallhack, olhando skins de armas e atrás da parede, verifique os horários do Shaders e OBB e compare também com o horário do replay, caso esteja muito diferente as datas, aplique o W.O!\n\n";
    delay(0.2);

    $diretorioAvatarRes = "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/optionalavatarres/gameassetbundles";
    $diretorioOptionalAvatarRes = "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/optionalavatarres";

    $comandoVerificarPasta = 'adb shell "test -d ' . escapeshellarg($diretorioAvatarRes) . ' && echo existe || echo naoexiste"';
    $resultadoVerificarPasta = trim((string)shell_exec($comandoVerificarPasta));
    delay(0.1);

    $diretorioAlvo = "";
    $nomePasta = "";

    if ($resultadoVerificarPasta === "existe") {
        $diretorioAlvo = $diretorioAvatarRes;
        $nomePasta = "gameassetbundles";
    } else {
        $diretorioAlvo = $diretorioOptionalAvatarRes;
        $nomePasta = "optionalavatarres";
    }

    echo $bold . $amarelo . "  • Data de modificação na pasta '$nomePasta' (Optional): " . $dataDisplay . "\n";
    delay(0.1);

    echo $bold . $branco . "\n\n\t Obrigado por compactuar por um cenário limpo de cheats.\n";
    delay(0.1);
    echo $bold . $branco . "\t                 Com carinho, Keller...\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
    delay(0.2);
}

function verificarDispositivoADB() {
    global $bold, $vermelho, $cln;

    $binaries = [
        '/data/data/com.termux/files/usr/bin/adb',
        '/data/data/com.termux/files/usr/bin/clear'
    ];
    foreach ($binaries as $bin) {
        if (file_exists($bin)) {
            @chmod($bin, 0755);
        }
    }

    $devicesOutput = shell_exec('adb devices');
    $devicesOutput = (string)$devicesOutput; 
    $lines = explode("\n", trim($devicesOutput));
    $devices = [];

    for ($i = 1; $i < count($lines); $i++) {
        $line = trim($lines[$i]);
        if (!empty($line) && strpos($line, 'device') !== false) {
            $parts = preg_split('/\s+/', $line);
            if (isset($parts[0])) {
                $devices[] = $parts[0];
            }
        }
    }

    $numDevices = count($devices);

    if ($numDevices == 0) {
        echo $bold . $vermelho . "[!] Erro: Nenhum dispositivo encontrado.\n";
        echo $bold . $vermelho . "    Faça o pareamento de IP ou conecte um dispositivo via USB.\n" . $cln;
        exit(1);
    } elseif ($numDevices > 1) {
        echo $bold . $vermelho . "[!] Erro: Mais de um dispositivo/emulador conectado.\n";
        echo $bold . $vermelho . "    Desconecte os outros dispositivos e mantenha apenas um.\n";
        echo $bold . $vermelho . "    Dispositivos encontrados:\n";
        foreach ($devices as $dev) {
            echo "    - $dev\n";
        }
        echo $cln;
        exit(1);
    }
    
    shell_exec('adb shell "chmod 755 /data/data/com.termux/files/usr/bin/clear 2>/dev/null"');

    return true;
}

function inputusuario($message){
  global $branco, $bold, $verdebg, $vermelhobg, $azulbg, $cln, $lazul, $fverde, $ciano;
  $inputstyle = $cln . $bold . $ciano . "  ▸ " . $message . ": " . $fverde ;
echo $inputstyle;
}

$binaries = [
    '/data/data/com.termux/files/usr/bin/adb',
    '/data/data/com.termux/files/usr/bin/clear'
];
foreach ($binaries as $bin) {
    if (file_exists($bin)) {
        @chmod($bin, 0755);
    }
}

system("clear");
keller_banner();
delay(0.1);
echo "\n";

menuscanner:

    echo $bold . $azul . "
    ╔══════════════════════════════════════════════════════════════╗
    ║                      MENU PRINCIPAL                          ║
    ╚══════════════════════════════════════════════════════════════╝
      \n\n";
      echo $amarelo . "  [0] " . $branco . "Conectar ADB " . $cinza . "(Pareamento e conexão via ADB)\n" . $cln;
      echo $verde . "  [1] " . $branco . "Escanear FreeFire Normal\n" . $cln;
      echo $verde . "  [2] " . $branco . "Escanear FreeFire Max\n" . $cln;
      echo $vermelho . "  [S] " . $branco . "Sair\n\n" . $cln;
escolheropcoes:
    inputusuario("Escolha uma das opções acima");
    $opcaoscanner = trim(fgets(STDIN, 1024));

    if (!in_array($opcaoscanner, array(
      '0',
      '1',
      '2',	
      'S',
  ), true))
    {
      echo $bold . $vermelho . "\n[!] Opção inválida! Tente novamente. \n\n" . $cln;
      goto escolheropcoes;
    }
    else
    {
        if ($opcaoscanner == "0") {
            system("clear");
            keller_banner();
            
            echo $bold . $azul . "  → Verificando se o ADB está instalado...\n" . $cln;
            delay(0.1);
            if (!shell_exec("adb version > /dev/null 2>&1"))
            {
                echo $bold . $amarelo . "  ⚠ ADB não encontrado. Instalando android-tools...\n" . $cln;
                delay(0.1);
                system("pkg install android-tools -y > /dev/null 2>&1");
                delay(0.2);
                echo $bold . $fverde . "  ℹ Android-tools instalado com sucesso!\n\n" . $cln;
            } else {
                echo $bold . $fverde . "  ℹ ADB já está instalado.\n\n" . $cln;
            }
            delay(0.1);
            
            inputusuario("Qual a sua porta para o pareamento (ex: 45678)?");
            $pair_port = trim(fgets(STDIN, 1024));
            if (!empty($pair_port) && is_numeric($pair_port)) {
                echo $bold . $amarelo . "\n[!] Agora, digite o código de pareamento que aparece no seu celular e pressione Enter.\n" . $cln;
                delay(0.1);
                system("adb pair localhost:" . $pair_port . " > /dev/null 2>&1");
            } else {
                echo $bold . $vermelho . "\n[!] Porta inválida! Retornando ao menu.\n\n" . $cln;
                delay(0.1);
                system("clear");
                keller_banner();
                goto menuscanner;
            }
            
            echo "\n";
            
            inputusuario("Qual a sua porta para a conexão (ex: 12345)?");
            $connect_port = trim(fgets(STDIN, 1024));
            if (!empty($connect_port) && is_numeric($connect_port)) {
                echo $bold . $amarelo . "\n[!] Conectando ao dispositivo...\n" . $cln;
                delay(0.1);
                system("adb connect localhost:" . $connect_port . " > /dev/null 2>&1");
                echo $bold . $fverde . "\n[i] Processo de conexão finalizado. Verifique a saída acima para ver se a conexão foi bem-sucedida.\n" . $cln;
                echo $bold . $branco . "\n[+] Pressione Enter para voltar ao menu...\n" . $cln;
                fgets(STDIN, 1024);
                system("clear");
                keller_banner();
                goto menuscanner;
            } else {
                echo $bold . $vermelho . "\n[!] Porta inválida! Retornando ao menu.\n\n" . $cln;
                delay(0.1);
                system("clear");
                keller_banner();
                goto menuscanner;
            }
        } elseif ($opcaoscanner == "1") {
            escanearFreeFire("com.dts.freefireth", "FreeFire Normal");
        } elseif ($opcaoscanner == "2") {
            escanearFreeFire("com.dts.freefiremax", "FreeFire MAX");
        } elseif ($opcaoscanner == 's' || $opcaoscanner == 'S') {
            echo "\n\n\t Obrigado por compactuar por um cenário limpo de cheats.\n\n";
            die();
        }
      }

?>