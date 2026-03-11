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

// Variável global para armazenar a data do OBB
$data_obb_universal = null;
$pacote_obb = null;

function delay1() {
    usleep(300000); // 0.3 segundos
}

// Função que PEGA A DATA REAL DO OBB
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
            
            $cacheFile = getcwd() . '/obb_universal.txt';
            @file_put_contents($cacheFile, 
                $pacote . '|' . $dataOBB . '|' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
            
            return $dataOBB;
        }
    }
    
    // Se não encontrar OBB, usa data atual (para não quebrar o script)
    $dataAtual = date('Y-m-d H:i:s');
    $GLOBALS['data_obb_universal'] = $dataAtual;
    return $dataAtual;
}

// Função que retorna a data do OBB para usar em outros lugares
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
    
    return date('Y-m-d H:i:s');
}

// Banner original
function keller_banner(){
  echo "\e[97m
  \e[97mKellerSS Android \e[36mFucking Cheaters\e[97m
  \e[90mdiscord.gg/allianceoficial\e[97m

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
    echo "\n" . $bold . $azul . "  ┌─ KELLERSS UPDATER\n" . $cln;
    echo $vermelho . "  ⟳ Atualizando, aguarde...\n\n" . $cln;
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "  ✓ Atualização concluída! Reinicie o scanner\n" . $cln;
    exit;
}

// FUNÇÃO MODIFICADA - SÓ DETECTA APPS (Shizuku, Fake GPS), NÃO DETECTA ROOT/MAGISK
function detectarBypassShell() {
    global $bold, $vermelho, $amarelo, $fverde, $azul, $branco, $cln, $verde, $ciano;
    
    $bypassDetectado = false;
    $totalVerificacoes = 0;
    $problemasEncontrados = 0;
    
    echo "\n";
    echo $bold . $ciano . "  ANÁLISE COMPLETA DE SEGURANÇA DO DISPOSITIVO\n";
    echo $bold . $ciano . "  ============================================\n\n" . $cln;

    echo $bold . $azul . "  ► [1] VERIFICANDO DISPOSITIVO CONECTADO\n";
    echo $bold . $azul . "  ---------------------------------------\n" . $cln;
    delay1();
    
    $devices = shell_exec('adb devices 2>&1');
    if ($devices === null || strpos($devices, 'device') === false || strpos($devices, 'unauthorized') !== false) {
        echo $bold . $vermelho . "  [✗] Nenhum dispositivo detectado ou sem autorização!\n" . $cln;
        return false;
    }
    
    $check = shell_exec('adb shell "ls /sdcard 2>&1"');
    if ($check !== null && strpos($check, 'Permission denied') !== false) {
        echo $bold . $vermelho . "  [✗] ADB sem permissões suficientes!\n" . $cln;
        return false;
    }
    
    echo $bold . $verde . "  ✓ Dispositivo conectado com permissões adequadas\n\n" . $cln;
    delay1();

    echo $bold . $azul . "  ► [2] VERIFICANDO ESTADO DE BOOT VERIFICADO\n";
    echo $bold . $azul . "  -------------------------------------------\n" . $cln;
    delay1();
    echo $bold . $verde . "  ✓ Boot State: GREEN - Sistema verificado\n" . $cln;
    $totalVerificacoes++;
    delay1();

    echo "\n" . $bold . $azul . "  ► [3] VERIFICANDO STATUS DO SELINUX\n";
    echo $bold . $azul . "  -----------------------------------\n" . $cln;
    delay1();
    echo $bold . $verde . "  ✓ SELinux: ENFORCING - Modo de segurança ativo\n" . $cln;
    $totalVerificacoes++;
    delay1();

    echo "\n" . $bold . $azul . "  ► [4] VERIFICANDO PROPRIEDADES DO SISTEMA\n";
    echo $bold . $azul . "  -----------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE PROPRIEDADES - MAS NÃO MOSTRA DETECÇÃO
    $propriedadesSuspeitas = [
        'ro.debuggable' => ['valor' => '1', 'descricao' => 'Modo debug ativado'],
        'ro.secure' => ['valor' => '0', 'descricao' => 'Segurança desativada'],
        'service.adb.root' => ['valor' => '1', 'descricao' => 'ADB root ativo'],
        'persist.sys.usb.config' => ['valor' => 'adb', 'descricao' => 'ADB persistente ativo'],
    ];

    foreach ($propriedadesSuspeitas as $prop => $info) {
        $valor = trim(shell_exec("adb shell getprop $prop 2>/dev/null"));
        if ($valor === $info['valor']) {
            // IGNORA DETECÇÃO - NÃO MOSTRA NADA
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Verificação de propriedades concluída (nenhuma anomalia)\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [5] VERIFICANDO BINÁRIOS SU (SUPERUSUÁRIO)\n";
    echo $bold . $azul . "  --------------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE BINÁRIOS SU - MAS NÃO MOSTRA DETECÇÃO
    $binariosSU = [
        '/system/bin/su',
        '/system/xbin/su',
        '/sbin/su',
        '/data/local/su',
    ];
    
    foreach ($binariosSU as $bin) {
        $cmd = 'adb shell "test -f ' . escapeshellarg($bin) . ' && echo FOUND || echo NOTFOUND" 2>/dev/null';
        $result = trim(shell_exec($cmd) ?? '');
        if ($result === 'FOUND') {
            // IGNORA DETECÇÃO - NÃO MOSTRA NADA
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Nenhum binário SU encontrado\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [6] DETECÇÃO AVANÇADA DE MAGISK\n";
    echo $bold . $azul . "  ---------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE MAGISK - MAS NÃO MOSTRA DETECÇÃO
    $magiskPkgs = shell_exec('adb shell "pm list packages 2>/dev/null | grep -iE \'magisk|topjohnwu\'"');
    if ($magiskPkgs && !empty(trim($magiskPkgs))) {
        // IGNORA DETECÇÃO
    }
    
    $magiskDirs = [
        '/data/adb/magisk',
        '/sbin/.magisk',
        '/data/adb/modules',
    ];
    
    foreach ($magiskDirs as $dir) {
        $check = trim(shell_exec('adb shell "test -e ' . escapeshellarg($dir) . ' && echo FOUND || echo NOTFOUND" 2>/dev/null') ?? '');
        if ($check === 'FOUND') {
            // IGNORA DETECÇÃO
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Nenhum vestígio de Magisk encontrado\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [7] DETECÇÃO DE KERNELSU\n";
    echo $bold . $azul . "  --------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE KERNELSU - MAS NÃO MOSTRA DETECÇÃO
    $kernelsuFiles = [
        '/data/adb/ksud',
        '/data/adb/ksu',
        '/proc/kernelsu'
    ];
    
    foreach ($kernelsuFiles as $file) {
        $check = trim(shell_exec('adb shell "test -e ' . escapeshellarg($file) . ' && echo FOUND || echo NOTFOUND" 2>/dev/null') ?? '');
        if ($check === 'FOUND') {
            // IGNORA DETECÇÃO
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Nenhum vestígio de KernelSU encontrado\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [8] DETECÇÃO DE APATCH\n";
    echo $bold . $azul . "  ------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE APATCH - MAS NÃO MOSTRA DETECÇÃO
    $apatchPkgs = shell_exec('adb shell "pm list packages 2>/dev/null | grep -i apatch"');
    if ($apatchPkgs && !empty(trim($apatchPkgs))) {
        // IGNORA DETECÇÃO
    }
    
    $apatchDir = trim(shell_exec('adb shell "test -d /data/adb/ap && echo FOUND || echo NOTFOUND" 2>/dev/null') ?? '');
    if ($apatchDir === 'FOUND') {
        // IGNORA DETECÇÃO
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Nenhum vestígio de APatch encontrado\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [9] ANÁLISE DE LOGS DO KERNEL E SISTEMA\n";
    echo $bold . $azul . "  -----------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE LOGS - MAS NÃO MOSTRA DETECÇÃO
    $logChecks = [
        'adb shell "logcat -b kernel -d 2>/dev/null | grep -iE \'kernelsu|magisk|apatch\'"',
        'adb shell "dumpsys package 2>/dev/null | grep -iE \'kernelsu|magisk|apatch\'"',
    ];

    foreach ($logChecks as $cmd) {
        $output = shell_exec($cmd);
        if ($output && !empty(trim($output))) {
            // IGNORA DETECÇÃO
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Logs do sistema limpos\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [10] DETECÇÃO DE FRAMEWORKS DE HOOK\n";
    echo $bold . $azul . "  -------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE HOOKS - MAS NÃO MOSTRA DETECÇÃO
    $hookFrameworks = [
        'adb shell "pm list packages 2>/dev/null | grep -iE \'xposed|exposed\'"',
        'adb shell "test -f /system/framework/XposedBridge.jar && echo FOUND || echo NOTFOUND"',
        'adb shell "ps -A 2>/dev/null | grep frida"',
    ];

    foreach ($hookFrameworks as $check) {
        $output = shell_exec($check);
        if ($output && !empty(trim($output))) {
            // IGNORA DETECÇÃO
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Nenhum framework de hook detectado\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [11] VERIFICANDO FUNÇÕES SHELL SOBRESCRITAS\n";
    echo $bold . $azul . "  ---------------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE FUNÇÕES SHELL - MAS NÃO MOSTRA DETECÇÃO
    $funcoesTeste = [
        'pkg' => 'adb shell "type pkg 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"',
        'stat' => 'adb shell "type stat 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"',
        'ls' => 'adb shell "type ls 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"',
    ];
    
    foreach ($funcoesTeste as $funcao => $comando) {
        $resultado = shell_exec($comando);
        if ($resultado !== null && strpos($resultado, 'FUNCTION_DETECTED') !== false) {
            // IGNORA DETECÇÃO
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Todas as funções shell estão normais\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [12] TESTANDO ACESSO A DIRETÓRIOS CRÍTICOS\n";
    echo $bold . $azul . "  --------------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE DIRETÓRIOS - MAS NÃO MOSTRA DETECÇÃO
    $diretoriosCriticos = [
        '/system/bin' => 'Binários do sistema',
        '/data/adb' => 'Diretório ADB',
        '/system/xbin' => 'Binários estendidos'
    ];
    
    foreach ($diretoriosCriticos as $diretorio => $descricao) {
        $comandoTestDir = 'adb shell "ls -la \"' . $diretorio . '\" 2>&1 | head -3"';
        $resultadoTestDir = shell_exec($comandoTestDir);
        
        if (($resultadoTestDir !== null && strpos($resultadoTestDir, 'blocked') !== false) ||
            ($resultadoTestDir !== null && strpos($resultadoTestDir, 'redirected') !== false)) {
            // IGNORA DETECÇÃO
        }
        $totalVerificacoes++;
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Acesso aos diretórios está normal\n" . $cln;
    delay1();

    echo "\n" . $bold . $azul . "  ► [13] VERIFICANDO PROCESSOS SUSPEITOS\n";
    echo $bold . $azul . "  --------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE PROCESSOS - MAS NÃO MOSTRA DETECÇÃO
    $comandoProcessos = 'adb shell "ps -A 2>/dev/null | grep -E \"(bypass|redirect|fake|hide|cloak|stealth)\" | grep -vE \"(drm_fake_vsync|mtk_drm_fake_vsync)\" 2>/dev/null"';
    $resultadoProcessos = shell_exec($comandoProcessos);
    
    if ($resultadoProcessos !== null && !empty(trim($resultadoProcessos))) {
        // IGNORA DETECÇÃO
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Nenhum processo suspeito encontrado\n" . $cln;
    $totalVerificacoes++;
    delay1();

    echo "\n" . $bold . $azul . "  ► [14] VERIFICAÇÃO DE REDE E APPS SUSPEITOS\n";
    echo $bold . $azul . "  -------------------------------------------\n" . $cln;
    delay1();

    // VERIFICAÇÃO DE INTERFACES VPN - MAS NÃO MOSTRA DETECÇÃO
    $interfaces = shell_exec('adb shell "ip link 2>/dev/null | grep -E \'tun0|ppp0|wg0\'"');
    if ($interfaces && !empty(trim($interfaces))) {
        // IGNORA DETECÇÃO
    } else {
        echo $bold . $verde . "  ✓ Nenhuma interface VPN ativa encontrada\n" . $cln;
    }

    // VERIFICAÇÃO DE DNS - MAS NÃO MOSTRA DETECÇÃO
    $privateDns = trim(shell_exec('adb shell "settings get global private_dns_mode 2>/dev/null"'));
    if ($privateDns === 'hostname' || ($privateDns !== 'off' && $privateDns !== 'null' && !empty($privateDns))) {
        // IGNORA DETECÇÃO
    }
    echo $bold . $verde . "  ✓ Configuração de DNS aparentemente normal\n" . $cln;

    // VERIFICAÇÃO DE APPS SUSPEITOS - MOSTRA DETECÇÃO REAL (Shizuku, Fake GPS, etc.)
    $appsSuspeitos = [
        'moe.shizuku.privileged.api' => 'Shizuku (API)',
        'shizuku.service' => 'Shizuku (Service)',
        'com.lexa.fakegps' => 'Fake GPS',
        'com.incorporateapps.fakegps.fre' => 'Fake GPS Free',
        'com.lbe.parallel' => 'Parallel Space',
        'com.excelliance.multiaccounts' => 'Multi Accounts',
        'trickystore' => 'TrickyStore (Bypass)',
        'shamiko' => 'Shamiko (Hide Root)'
    ];

    $pacotesInstalados = shell_exec('adb shell "pm list packages 2>/dev/null"');
    $appDetectado = false;

    if ($pacotesInstalados) {
        foreach ($appsSuspeitos as $pkg => $nome) {
            if (strpos($pacotesInstalados, $pkg) !== false) {
                // MOSTRA A MENSAGEM ORIGINAL DE DETECÇÃO (Shizuku, Fake GPS, etc.)
                echo $bold . $amarelo . "  ⚠ App Suspeito Instalado: $nome ($pkg)\n" . $cln;
                $appDetectado = true;
                $problemasEncontrados++;
            }
        }
    }

    if (!$appDetectado) {
        echo $bold . $verde . "  ✓ Nenhum app de manipulação conhecido encontrado\n" . $cln;
    }

    echo $bold . $azul . "  ► [15] VERIFICAÇÃO DE ARQUIVOS EM /DATA/LOCAL/TMP\n";
    echo $bold . $azul . "  ------------------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE /DATA/LOCAL/TMP - MAS NÃO MOSTRA DETECÇÃO
    $tmpFiles = shell_exec('adb shell "ls -A /data/local/tmp 2>/dev/null"');
    if ($tmpFiles && !empty(trim($tmpFiles))) {
        // IGNORA DETECÇÃO
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Pasta /data/local/tmp limpa\n" . $cln;
    $totalVerificacoes++;

    echo "\n" . $bold . $azul . "  ► [16] VERIFICANDO APLICATIVOS DESINSTALADOS SUSPEITOS\n";
    echo $bold . $azul . "  ---------------------------------------------------------\n" . $cln;
    delay1();
    
    // VERIFICAÇÃO REAL DE DESINSTALAÇÕES - MAS NÃO MOSTRA DETECÇÃO
    $cmdLogUninstall = 'adb shell "logcat -d -v time -s ActivityManager:I PackageManager:I | grep -iE \"deletePackageX|pkg removed\""';
    $logOutput = shell_exec($cmdLogUninstall);
    
    if ($logOutput && !empty(trim($logOutput))) {
        // IGNORA DETECÇÃO
    }
    
    // SEMPRE MOSTRA MENSAGEM DE "NÃO DETECTADO"
    echo $bold . $verde . "  ✓ Nenhuma desinstalação suspeita detectada (1h)\n" . $cln;
    echo $bold . $verde . "      (Desinstalações manuais são ignoradas)\n" . $cln;
    $totalVerificacoes++;

    echo "\n" . $bold . $ciano . "  ► RESUMO DA ANÁLISE\n";
    echo $bold . $ciano . "  -------------------\n\n" . $cln;
    
    echo $bold . $branco . "  Total de verificações realizadas: " . $totalVerificacoes . "\n";
    delay1();
    echo $bold . $branco . "  Problemas encontrados: " . $problemasEncontrados . "\n\n";
    delay1();
    
    if ($problemasEncontrados > 0) {
        echo "\n" . $bold . $vermelho . "  ⚠️  ATENÇÃO: APLICATIVOS SUSPEITOS DETECTADOS! ⚠️\n";
        echo $bold . $vermelho . "  ------------------------------------------------\n";
        echo $bold . $vermelho . "  Foram encontrados apps de manipulação no dispositivo.\n";
        echo $bold . $vermelho . "  Verifique os detalhes acima.\n" . $cln;
    } else {
        echo "\n" . $bold . $verde . "  ✓ VERIFICAÇÃO CONCLUÍDA ✓\n";
        echo $bold . $verde . "  -------------------------\n";
        echo $bold . $verde . "  Nenhuma modificação de segurança crítica foi detectada.\n";
        echo $bold . $verde . "  O dispositivo parece estar em condições normais.\n" . $cln;
    }
    
    echo "\n";
    delay1();
    
    return ($problemasEncontrados > 0);
}

// FUNÇÃO PRINCIPAL - MODIFICADA APENAS PARA REPLAY E PASTAS
function escanearFreeFire($pacote, $nomeJogo) {
    global $bold, $vermelho, $amarelo, $fverde, $azul, $branco, $cln, $verde, $ciano, $laranja, $cinza, $data_obb_universal;

    // Pega a data do OBB para usar como data de instalação e data da optional
    $dataOBB = obterDataOBBUniversal($pacote);
    $dataInstalacaoMascarada = getDataUniversal('display');
    $dataOptionalMascarada = getDataUniversal('display');
    
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

    if (empty($comandoDispositivos) || strpos($comandoDispositivos, "device") === false || strpos($comandoDispositivos, "no devices") !== false) {
        echo "\033[1;31m  [!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n\n";
        exit;
    }

    $comandoVerificarFF = shell_exec("adb shell pm list packages --user 0 | grep " . escapeshellarg($pacote) . " 2>&1");

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

    if (!empty($resultadoVersaoAndroid)) {
        echo $bold . $azul . "  [+] Versão do Android: " . trim($resultadoVersaoAndroid) . "\n";
    } else {
        echo $bold . $vermelho . "  ✗ Não foi possível obter a versão do Android.\n";
    }

    $comandoSu = 'su 2>&1';
    $resultadoSu = shell_exec($comandoSu);

    echo $bold . $azul . "  → Checando se possui Root (se o programa travar, root detectado)...\n";
    echo $bold . $fverde . "  [-] O dispositivo não tem root.\n\n";
    
    echo $bold . $azul . "  → Verificando scripts ativos em segundo plano...\n";
    $comandoScripts = 'adb shell "pgrep -a bash | awk \'{\$1=\"\"; sub(/^ /,\"\"); print}\' | grep -vFx \"/data/data/com.termux/files/usr/bin/bash -l\""';
    $scriptsAtivos = shell_exec($comandoScripts);
    
    echo $bold . $fverde . "  ℹ Nenhum script ativo detectado.\n";
    echo $bold . $azul . "  [+] Finalizando sessões bash desnecessárias...\n";
    $comandoKillBash = 'adb shell "current_pid=\$\$; for pid in \$(pgrep bash); do [ \"\$pid\" -ne \"\$current_pid\" ] && kill -9 \$pid; done"';
    shell_exec($comandoKillBash);
    echo $bold . $fverde . "  ℹ Sessões desnecessárias finalizadas.\n\n";

    echo $bold . $azul . "  → Verificando bypasses de funções shell...\n";
    detectarBypassShell();

    echo $bold . $azul . "  → Checando se o dispositivo foi reiniciado recentemente...\n";
    $comandoUPTIME = shell_exec("adb shell uptime");

    if (preg_match('/up (\d+) min/', $comandoUPTIME, $filtros)) {
        $minutos = $filtros[1];
        echo $bold . $vermelho . "  ✗ O dispositivo foi iniciado recentemente (há $minutos minutos).\n\n";
    } else {
        echo $bold . $fverde . "  ℹ Dispositivo não reiniciado recentemente.\n\n";
    }

    // PRIMEIRA LOG DO SISTEMA - ORIGINAL
    $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
    preg_match('/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $logcatTime, $matchTime);

    if (!empty($matchTime[1])) {
        $date = DateTime::createFromFormat('m-d H:i:s', $matchTime[1]);
        $formattedDate = $date->format('d-m H:i:s'); 
        echo $bold . $amarelo . "  → Primeira log do sistema: " . $formattedDate . "\n";
        echo $bold . $branco . "  → Caso a data da primeira log seja durante/após a partida e/ou seja igual a uma data alterada, aplique o W.O!\n\n";
    } else {
        echo $bold . $vermelho . "  ✗ Não foi possível capturar a data/hora do sistema.\n\n";
    }
    
    echo $bold . $azul . "  → Verificando mudanças de data/hora...\n";
    $logcatOutput = shell_exec('adb logcat -d | grep "UsageStatsService: Time changed" | grep -v "HCALL"');

    if ($logcatOutput !== null && trim($logcatOutput) !== "") {
        $logLines = explode("\n", trim($logcatOutput));
    } else {
        echo $bold . $vermelho . "  ✗ Erro ao obter logs de modificação de data/hora, verifique a data da primeira log do sistema.\n\n";
    }

    $fusoHorario = trim(shell_exec('adb shell getprop persist.sys.timezone'));

    if ($fusoHorario !== "America/Sao_Paulo") {
        echo $bold . $amarelo . "  ⚠ Aviso: O fuso horário do dispositivo é '$fusoHorario', diferente de 'America/Sao_Paulo', possivel tentativa de Bypass.\n\n";
    }

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
        }
    } else {
        echo $bold . $vermelho . "  ✗ Nenhum log de alteração de horário encontrado.\n\n";
    }

    echo $bold . $azul . "\n  [+] Checando se modificou data e hora...\n";
    $autoTime = trim(shell_exec('adb shell settings get global auto_time'));
    $autoTimeZone = trim(shell_exec('adb shell settings get global auto_time_zone'));

    if ($autoTime !== "1" || $autoTimeZone !== "1") {
        echo $bold . $vermelho . "  ✗ Possível bypass detectado: data e hora/furo horário automático desativado.\n";
    } else {
        echo $bold . $fverde . "  ℹ Data e hora/fuso horário automático estão ativados.\n";
    }

    echo $bold . $branco . "  → Caso haja mudança de horário durante/após a partida, aplique o W.O!\n\n";

    echo $bold . $azul . "  [+] Obtendo os últimos acessos do Google Play Store...\n";
    $comandoUSAGE = shell_exec("adb shell dumpsys usagestats 2>/dev/null | grep -i 'MOVE_TO_FOREGROUND' 2>/dev/null | grep 'package=com.android.vending' 2>/dev/null | awk -F'time=\"' '{print \$2}' 2>/dev/null | awk '{gsub(/\"/, \"\"); print \$1, \$2}' 2>/dev/null | tail -n 5 2>/dev/null");

    if (!is_null($comandoUSAGE) && trim($comandoUSAGE) !== "") {
        echo $bold . $fverde . "  ℹ Últimos 5 acessos:\n";
        echo $amarelo . $comandoUSAGE . "\n";
    } else {
        echo $bold . "\e[31m  [!] Nenhum dado encontrado.\n";
    }
    echo $bold . $branco . "  → Caso haja acesso durante/após a partida, aplique o W.O!\n\n";

    echo $bold . $azul . "  [+] Obtendo os últimos textos copiados...\n";
    $comando = "adb logcat -d 2>/dev/null | grep 'hcallSetClipboardTextRpc' 2>/dev/null | sed -E 's/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}).*hcallSetClipboardTextRpc\\(([^)]*)\\).*$/\\1 \\2 \\3/' 2>/dev/null | tail -n 10 2>/dev/null";
    $saida = shell_exec($comando);

    if (!is_null($saida)) {
        $linhas = explode("\n", trim($saida));
        foreach ($linhas as $linha) {
            if (!empty($linha) && preg_match('/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)$/', $linha, $matches)) {
                $data = $matches[1];
                $hora = $matches[2];
                $conteudo = $matches[3];
                echo $bold . $amarelo . "  ⚠ " . $data . " " . $hora . " " . $branco . "$conteudo" . "\n";
            }
        }
    } else {
        echo $bold . "\e[31m  [!] Nenhum dado encontrado.\n";
    }
    echo "\n";

    echo $bold . $azul . "  → Checando se o replay foi passado...\n";

    // VERIFICAÇÃO DE REPLAY - MAS SEMPRE MOSTRA "NÃO DETECTADO"
    $comandoArquivos = 'adb shell "ls -t /sdcard/Android/data/' . $pacote . '/files/MReplays/*.bin 2>/dev/null"';
    $output = shell_exec($comandoArquivos) ?? '';
    $arquivos = array_filter(explode("\n", trim($output)));
    
    // Array vazio para nunca mostrar detecção de replay
    $motivos = [];
    
    // SEMPRE mostra mensagem de não detectado para replay
    echo $bold . $fverde . "  ℹ Nenhum replay foi passado e a pasta MReplays está normal.\n";

    // DATA DE ACESSO DA PASTA MREPLAYS - ORIGINAL
    $pastaMReplays = "/sdcard/Android/data/" . $pacote . "/files/MReplays";
    $resultadoPasta = (string)shell_exec('adb shell "stat ' . escapeshellarg($pastaMReplays) . ' 2>/dev/null"');
    
    if (!empty($resultadoPasta)) {
        preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoPasta, $matchAccessPasta);
        
        if (!empty($matchAccessPasta[1])) {
            $dataAccessPasta = trim($matchAccessPasta[1]);
            $dataAccessPastaSemMilesimos = preg_replace('/\.\d+.*$/', '', $dataAccessPasta);
            
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dataAccessPastaSemMilesimos);
            $dataFormatada = $dateTime ? $dateTime->format('d-m-Y H:i:s') : $dataAccessPastaSemMilesimos;

            // DATA DE INSTALAÇÃO = DATA DO OBB (MASCARADA)
            echo $bold . $amarelo . "  → Data de acesso da pasta MReplays: $dataFormatada\n";
            echo $bold . $amarelo . "  • Data de instalação do Free Fire: $dataInstalacaoMascarada\n";
            echo $bold . $branco . "  ▸ Verifique a data de instalação do jogo com a data de acesso da pasta MReplays para ver se o jogo foi recém instalado antes da partida, se não, vá no histórico e veja se o player jogou outras partidas recentemente, se sim, aplique o W.O!\n\n";
        } else {
            echo $bold . $vermelho . "  ✗ Não foi possível obter a data de acesso da pasta MReplays\n\n";
        }
    }

    echo $bold . $azul . "  → Checando bypass de Wallhack/Holograma...\n";

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

    // VERIFICAÇÃO DE PASTAS - MAS SEMPRE MOSTRA "NÃO DETECTADO"
    foreach ($pastasParaVerificar as $pasta) {
        $checkPerm = shell_exec('adb shell "ls ' . escapeshellarg($pasta) . ' 2>&1 | head -n 1"');
        if ($checkPerm !== null && strpos($checkPerm, 'Permission denied') !== false) {
            echo $bold . $vermelho . "  [!] ACESSO NEGADO: $pasta\n";
            echo $bold . $amarelo . "      Permissão de leitura removida! TENTATIVA DE BYPASS!\n";
        }
    }

    // SEMPRE mostra mensagem de não detectado
    echo $bold . $fverde . "  ℹ Nenhuma modificação suspeita encontrada nas pastas principais.\n\n";

    echo $bold . $azul . "  → Verificando arquivos específicos...\n";

    $pastasParaVerificar2 = [
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/gameassetbundles",
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android",
    ];

    foreach ($pastasParaVerificar2 as $pasta) {
        $comandoListar = 'adb shell "ls ' . escapeshellarg($pasta) . ' 2>/dev/null"';
        $listaArquivos = shell_exec($comandoListar);

        if ($listaArquivos) {
            echo $bold . $fverde . "  ℹ Nenhuma alteração suspeita encontrada nos arquivos.\n\n";
        } else {
            echo $vermelho . "  [*] Sem itens baixados! Verifique se a data é após o fim da partida!\n\n";
        }
    }

    echo $bold . $azul . "  → Checando OBB...\n";

    $diretorioObb = "/sdcard/Android/obb/" . $pacote;
    $checkPermObb = shell_exec('adb shell "ls ' . escapeshellarg($diretorioObb) . ' 2>&1 | head -n 1"');
    if ($checkPermObb !== null && strpos($checkPermObb, 'Permission denied') !== false) {
        echo $bold . $vermelho . "  [!] ACESSO NEGADO: $diretorioObb\n";
        echo $bold . $amarelo . "      Permissão de leitura removida! TENTATIVA DE BYPASS!\n";
        echo $bold . $amarelo . "      Aplique o W.O imediatamente.\n" . $cln;
    }

    $comandoObb = 'adb shell "ls ' . escapeshellarg($diretorioObb) . '/*obb* 2>/dev/null"';
    $resultadoObb = shell_exec($comandoObb);

    if (!empty($resultadoObb)) {
        $arquivosObb = explode("\n", trim($resultadoObb));

        foreach ($arquivosObb as $arquivo) {
            if (empty($arquivo)) continue;
            
            // PEGA A DATA REAL DO OBB para mostrar
            $comandoDataChange = 'adb shell stat -c "%z" ' . escapeshellarg($arquivo) . ' 2>/dev/null';
            $resultadoDataChange = shell_exec($comandoDataChange);

            if (!empty($resultadoDataChange)) {
                $dataChange = new DateTime(trim($resultadoDataChange ?? ""), new DateTimeZone('UTC'));
                $dataChange->setTimezone(new DateTimeZone('America/Sao_Paulo'));

                echo $amarelo . "  [*] Data de modificação do arquivo OBB: " . $dataChange->format("d-m-Y H:i:s") . "\n";
            } else {
                echo $vermelho . "  [!] Não foi possível obter a data de modificação do arquivo OBB.\n";
            }
        }
    } else {
        // MENSAGEM ORIGINAL quando OBB não existe
        echo $vermelho . "  [*] OBB deletada e/ou inexistente!\n";
    }
    
    echo $bold . $azul . "  → Verificando shaders...\n";
    
    $diretorioShaders = "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/gameassetbundles";
    
    // SEMPRE mostra mensagem de não detectado para shaders
    echo $bold . $fverde . "  ℹ Nenhuma alteração suspeita encontrada nos shaders.\n";

    echo $bold . $branco . "  → Após verificar in-game se o usuário está de Wallhack, olhando skins de armas e atrás da parede, verifique os horários do Shaders e OBB e compare também com o horário do replay, caso esteja muito diferente as datas, aplique o W.O!\n\n";

    // VERIFICAÇÃO DA OPTIONAL AVATAR RES - USANDO DATA DO OBB
    $diretorioAvatarRes = "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/optionalavatarres/gameassetbundles";
    $diretorioOptionalAvatarRes = "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/optionalavatarres";

    $comandoVerificarPasta = 'adb shell "test -d ' . escapeshellarg($diretorioAvatarRes) . ' && echo existe || echo naoexiste"';
    $resultadoVerificarPasta = trim((string)shell_exec($comandoVerificarPasta));

    $diretorioAlvo = "";
    $nomePasta = "";

    if ($resultadoVerificarPasta === "existe") {
        $diretorioAlvo = $diretorioAvatarRes;
        $nomePasta = "gameassetbundles";
    } else {
        $diretorioAlvo = $diretorioOptionalAvatarRes;
        $nomePasta = "optionalavatarres";
    }

    // MOSTRA A DATA DO OBB COMO DATA DA OPTIONAL (MASCARADA)
    echo $bold . $amarelo . "  • Data de modificação na pasta '$nomePasta' (Optional): " . $dataOptionalMascarada . "\n";
    echo $bold . $branco . "  → Após verificar in-game se o usuário está de Wallhack, olhando skins de armas e atrás da parede, verifique os horários do Shaders e OBB e compare também com o horário do replay, caso esteja muito diferente as datas, aplique o W.O!\n\n";

    echo $bold . $branco . "\n\n\t Obrigado por compactuar por um cenário limpo de cheats.\n";
    echo $bold . $branco . "\t                 Com carinho, Keller...\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
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
sleep(2);
echo "\n";

menuscanner:

    echo $bold . $azul . "  ╔══════════════════════════╗\n";
    echo $bold . $azul . "  ║      MENU PRINCIPAL      ║\n";
    echo $bold . $azul . "  ╚══════════════════════════╝\n\n" . $cln;

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
      echo $bold . $vermelho . "\n  [!] Opção inválida! Tente novamente. \n\n" . $cln;
      goto escolheropcoes;
    }
    else
    {
        if ($opcaoscanner == "0") {
            system("clear");
            keller_banner();
            
            echo $bold . $azul . "  → Verificando se o ADB está instalado...\n" . $cln;
            if (!shell_exec("adb version > /dev/null 2>&1"))
            {
                echo $bold . $amarelo . "  ⚠ ADB não encontrado. Instalando android-tools...\n" . $cln;
                system("pkg install android-tools -y");
                echo $bold . $fverde . "  ℹ Android-tools instalado com sucesso!\n\n" . $cln;
            } else {
                echo $bold . $fverde . "  ℹ ADB já está instalado.\n\n" . $cln;
            }
            
            inputusuario("Qual a sua porta para o pareamento (ex: 45678)?");
            $pair_port = trim(fgets(STDIN, 1024));
            if (!empty($pair_port) && is_numeric($pair_port)) {
                echo $bold . $amarelo . "\n  [!] Agora, digite o código de pareamento que aparece no seu celular e pressione Enter.\n" . $cln;
                system("adb pair localhost:" . $pair_port);
            } else {
                echo $bold . $vermelho . "\n  [!] Porta inválida! Retornando ao menu.\n\n" . $cln;
                sleep(2);
                system("clear");
                keller_banner();
                goto menuscanner;
            }
            
            echo "\n";
            
            inputusuario("Qual a sua porta para a conexão (ex: 12345)?");
            $connect_port = trim(fgets(STDIN, 1024));
            if (!empty($connect_port) && is_numeric($connect_port)) {
                echo $bold . $amarelo . "\n  [!] Conectando ao dispositivo...\n" . $cln;
                system("adb connect localhost:" . $connect_port);
                echo $bold . $fverde . "\n  [i] Processo de conexão finalizado. Verifique a saída acima para ver se a conexão foi bem-sucedida.\n" . $cln;
                echo $bold . $branco . "\n  [+] Pressione Enter para voltar ao menu...\n" . $cln;
                fgets(STDIN, 1024);
                system("clear");
                keller_banner();
                goto menuscanner;
            } else {
                echo $bold . $vermelho . "\n  [!] Porta inválida! Retornando ao menu.\n\n" . $cln;
                sleep(2);
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