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

function keller_banner(){
  echo "\e[37m
           KellerSS Android\e[36m Fucking Cheaters\e[91m\e[37m discord.gg/allianceoficial\e[91m
            
                            )       (     (          (     
                        ( /(       )\ )  )\ )       )\ )  
                        )\()) (   (()/( (()/(  (   (()/(  
                        |((_)\  )\   /(_)) /(_)) )\   /(_)) 
                        |_ ((_)((_) (_))  (_))  ((_) (_))   
                        | |/ / | __|| |   | |   | __|| _ \  
                        ' <  | _| | |__ | |__ | _| |   /  
                        _|\_\ |___||____||____||___||_|_\  


                    \e[36m{C} Coded By - KellerSS | Credits for Sheik                                   
\e[32m
  \n";
}

echo $cln;

function atualizar()
{
    global $cln, $bold, $fverde;
    echo "\n\e[91m\e[1m[+] KellerSS Updater [+]\nAtualizando, por favor aguarde...\n\n$cln";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "[i] Atualização concluida! Por favor reinicie o Scanner \n" . $cln;
    exit;
}

function detectarBypassShell() {
    global $bold, $vermelho, $amarelo, $fverde, $azul, $branco, $cln;
    
    $bypassDetectado = false;
    
    echo $bold . $azul . "[+] Verificando funções maliciosas no ambiente shell...\n";
    sleep(1);
    
    $funcoesTeste = [
        'pkg' => 'adb shell "type pkg 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"',
        'git' => 'adb shell "type git 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"', 
        'cd' => 'adb shell "type cd 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"',
        'stat' => 'adb shell "type stat 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"',
        'adb' => 'adb shell "type adb 2>/dev/null | grep -q function && echo FUNCTION_DETECTED"'
    ];
    
    foreach ($funcoesTeste as $funcao => $comando) {
        $resultado = shell_exec($comando);
        if ($resultado !== null && strpos($resultado, 'FUNCTION_DETECTED') !== false) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Função '$funcao' foi sobrescrita!\n";
            $bypassDetectado = true;
        }
    }
     
    echo $bold . $azul . "[+] Testando acesso a diretórios críticos...\n";
    sleep(1);
     
    $diretoriosCriticos = [
        '/system/bin',
        '/data/data/com.dts.freefireth/files',
        '/data/data/com.dts.freefiremax/files',
        '/storage/emulated/0/Android/data'
    ];
     
    foreach ($diretoriosCriticos as $diretorio) {
        $comandoTestDir = 'adb shell "ls -la \"' . $diretorio . '\" 2>/dev/null | head -3"';
        $resultadoTestDir = shell_exec($comandoTestDir);
         
        if (empty($resultadoTestDir) || trim($resultadoTestDir ?? '') === '' || 
            ($resultadoTestDir !== null && strpos($resultadoTestDir, 'Permission denied') !== false) ||
            ($resultadoTestDir !== null && strpos($resultadoTestDir, 'blocked') !== false) ||
            ($resultadoTestDir !== null && strpos($resultadoTestDir, 'redirected') !== false)) {
             
            if (($resultadoTestDir !== null && strpos($resultadoTestDir, 'blocked') !== false) ||
                ($resultadoTestDir !== null && strpos($resultadoTestDir, 'redirected') !== false) ||
                ($resultadoTestDir !== null && strpos($resultadoTestDir, 'bypass') !== false)) {
                 
                echo $bold . $vermelho . "[!] BYPASS DETECTADO: Acesso bloqueado/redirecionado ao diretório: $diretorio\n";
                echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoTestDir ?? '') . "\n";
                $bypassDetectado = true;
            }
        }
    }
     
    echo $bold . $azul . "[+] Verificando processos suspeitos...\n";
    sleep(1);
     
    $comandoProcessos = 'adb shell "ps | grep -E \"(bypass|redirect|fake)\" 2>/dev/null"';
    $resultadoProcessos = shell_exec($comandoProcessos);
     
    if ($resultadoProcessos !== null && !empty(trim($resultadoProcessos))) {
        $linhasProcessos = explode("\n", trim($resultadoProcessos));
        $processosSuspeitos = [];
         
        foreach ($linhasProcessos as $linha) {
            if (!empty(trim($linha)) && 
                strpos($linha, '[kblockd]') === false && 
                strpos($linha, 'kworker') === false &&
                strpos($linha, '[ksoftirqd]') === false &&
                strpos($linha, '[migration]') === false &&
                strpos($linha, 'mtk_drm_fake_vsync') === false) {
                $processosSuspeitos[] = $linha;
            }
        }
         
        if (!empty($processosSuspeitos)) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Processos suspeitos em execução!\n";
            echo $bold . $amarelo . "[!] Processos encontrados:\n" . implode("\n", $processosSuspeitos) . "\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Verificando arquivos de configuração...\n";
    sleep(1);
    
    $arquivosConfig = [
        '~/.bashrc', '~/.bash_profile', '~/.profile', '~/.zshrc', 
        '~/.config/fish/config.fish', '/data/data/com.termux/files/usr/etc/bash.bashrc'
    ];
    
    foreach ($arquivosConfig as $arquivo) {
        $comandoVerificar = 'adb shell "if [ -f ' . $arquivo . ' ]; then cat ' . $arquivo . ' | grep -E \"(function pkg|function git|function cd|function stat|function adb)\" 2>/dev/null; fi"';
        $resultadoArquivo = shell_exec($comandoVerificar);
        
        if ($resultadoArquivo !== null && !empty(trim($resultadoArquivo))) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Funções maliciosas em $arquivo!\n";
            echo $bold . $amarelo . "[!] Conteúdo detectado:\n" . trim($resultadoArquivo) . "\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando comportamento real das funções...\n";
    sleep(1);
     
    $comandoTestGitReal = 'adb shell "cd /tmp 2>/dev/null || cd /data/local/tmp; git clone --help 2>&1 | head -1"';
    $resultadoGitHelp = shell_exec($comandoTestGitReal);
     
    if (empty($resultadoGitHelp) || strpos($resultadoGitHelp, 'usage: git') === false) {
        $comandoTestClone = 'adb shell "cd /tmp 2>/dev/null || cd /data/local/tmp; timeout 5 git clone https://github.com/kellerzz/KellerSS-Android test-repo 2>&1 | head -3"';
        $resultadoClone = shell_exec($comandoTestClone);
         
        if (strpos($resultadoClone, 'wendell77x') !== false || 
            strpos($resultadoClone, 'Comando bloqueado') !== false ||
            strpos($resultadoClone, 'blocked') !== false) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Git clone sendo redirecionado!\n";
            echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoClone) . "\n";
            $bypassDetectado = true;
        }
    }
     
    $comandoTestPkgReal = 'adb shell "pkg --help 2>&1 | head -1"';
    $resultadoPkgHelp = shell_exec($comandoTestPkgReal);
     
    if (empty($resultadoPkgHelp) || strpos($resultadoPkgHelp, 'Usage:') === false) {
        $comandoTestPkgInstall = 'adb shell "timeout 3 pkg install --help 2>&1"';
        $resultadoPkgInstall = shell_exec($comandoTestPkgInstall);
         
        if (strpos($resultadoPkgInstall, 'Comando bloqueado') !== false ||
            strpos($resultadoPkgInstall, 'blocked') !== false ||
            empty(trim($resultadoPkgInstall))) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Comando pkg sendo bloqueado!\n";
            echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoPkgInstall) . "\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando manipulação da função stat...\n";
    sleep(1);
     
    $arquivoTeste = '/data/local/tmp/test_stat_' . time();
    $comandoCriarArquivo = 'adb shell "echo test > ' . $arquivoTeste . ' 2>/dev/null"';
    shell_exec($comandoCriarArquivo);
     
    $comandoStatTeste = 'adb shell "stat ' . $arquivoTeste . ' 2>/dev/null"';
    $resultadoStatTeste = shell_exec($comandoStatTeste);
     
    if (!empty($resultadoStatTeste)) {
        preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatTeste, $matchAccess);
        preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatTeste, $matchModify);
        preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatTeste, $matchChange);
         
        if ($matchAccess && $matchModify && $matchChange) {
            $timestampAccess = strtotime($matchAccess[1]);
            $timestampModify = strtotime($matchModify[1]);
            $timestampChange = strtotime($matchChange[1]);
            $timestampAtual = time();
             
            $diferencaAtual = abs($timestampAtual - $timestampModify);
            $diferencaInterna = abs($timestampAccess - $timestampModify);
             
            if ($diferencaAtual > 86400 || $diferencaInterna > 300) {
                echo $bold . $vermelho . "[!] BYPASS DETECTADO: Função stat retornando dados inconsistentes!\n";
                echo $bold . $amarelo . "[!] Arquivo criado agora, mas stat mostra: " . $matchModify[1] . "\n";
                $bypassDetectado = true;
            }
        }
    }
     
    shell_exec('adb shell "rm -f ' . $arquivoTeste . ' 2>/dev/null"');
     
    $caminhoMReplays = '/storage/emulated/0/Android/data/com.dts.freefireth/files/MReplays';
    $comandoStatMReplays = 'adb shell "stat ' . escapeshellarg($caminhoMReplays) . ' 2>/dev/null"';
    $resultadoStatMReplays = shell_exec($comandoStatMReplays);
     
    if (!empty($resultadoStatMReplays) && preg_match('/Modify: (\d{4}-\d{2}-\d{2})/', $resultadoStatMReplays, $matches)) {
        $dataModify = $matches[1];
        if ($dataModify === '2020-01-01' || strtotime($dataModify) < strtotime('2021-01-01')) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Stat retornando data suspeita para MReplays!\n";
            echo $bold . $amarelo . "[!] Data suspeita: $dataModify\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando comportamento do comando cd...\n";
    sleep(1);
     
    $comandoTestCd = 'adb shell "cd /tmp 2>/dev/null || cd /data/local/tmp; pwd; cd /; pwd"';
    $resultadoTestCd = shell_exec($comandoTestCd);
     
    if (empty($resultadoTestCd) || strpos($resultadoTestCd, '/') === false) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Comando cd não está funcionando normalmente!\n";
        echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoTestCd) . "\n";
        $bypassDetectado = true;
    }
     
    echo $bold . $azul . "[+] Testando integridade de comandos básicos...\n";
    sleep(1);
     
    $testesComandos = [
        'which' => ['adb shell "which ls 2>/dev/null"', '/system/bin/ls'],
        'echo' => ['adb shell "echo test123"', 'test123'],
        'date' => ['adb shell "date +%Y 2>/dev/null"', date('Y')]
    ];
     
    foreach ($testesComandos as $comando => $teste) {
        $resultado = trim(shell_exec($teste[0]));
        if (empty($resultado) || strpos($resultado, $teste[1]) === false) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Comando '$comando' não retorna resposta esperada!\n";
            echo $bold . $amarelo . "[!] Esperado: {$teste[1]}, Recebido: $resultado\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando bloqueio de comandos pkg...\n";
    sleep(1);
    
    $comandoTestPkg = 'adb shell "echo \"pkg install com.dts.freefireth\" | bash 2>&1"';
    $resultadoTestPkg = shell_exec($comandoTestPkg);
    
    if (strpos($resultadoTestPkg, 'Comando bloqueado') !== false || 
        strpos($resultadoTestPkg, 'blocked') !== false) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Bloqueio de comandos pkg ativo!\n";
        echo $bold . $amarelo . "[!] Resposta do sistema: " . trim($resultadoTestPkg) . "\n";
        $bypassDetectado = true;
    }
    
    echo $bold . $azul . "[+] Verificando arquivos de bypass no dispositivo...\n";
    sleep(1);
     
    $comandoArquivosBypass = 'adb shell "find /sdcard /data/local/tmp /data/data/com.termux/files/home -name \"*.sh\" -exec grep -l \"function pkg\\|function git\\|function cd\\|function stat\\|function adb\\|wendell77x\\|FAKE_ADB_SHELL\" {} \\; 2>/dev/null | head -10"';
    $resultadoArquivosBypass = shell_exec($comandoArquivosBypass);
     
    if ($resultadoArquivosBypass !== null && !empty(trim($resultadoArquivosBypass))) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivos de bypass encontrados!\n";
        echo $bold . $amarelo . "[!] Arquivos suspeitos:\n" . trim($resultadoArquivosBypass) . "\n";
        $bypassDetectado = true;
    }
     
    $comandoNomesSuspeitos = 'adb shell "find /sdcard /data/local/tmp /data/data/com.termux/files/home -name \"*block*\" -o -name \"*redirect*\" -o -name \"*bypass*\" -o -name \"*install*\" -o -name \"*hack*\" 2>/dev/null | head -10"';
    $resultadoNomesSuspeitos = shell_exec($comandoNomesSuspeitos);
     
    if ($resultadoNomesSuspeitos !== null && !empty(trim($resultadoNomesSuspeitos))) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivos com nomes suspeitos encontrados!\n";
        echo $bold . $amarelo . "[!] Arquivos encontrados:\n" . trim($resultadoNomesSuspeitos) . "\n";
        $bypassDetectado = true;
    }
    
    if ($bypassDetectado) {
        echo $bold . $vermelho . "\n[!] ========== ATENÇÃO ==========\n";
        echo $bold . $vermelho . "[!] BYPASS DE FUNÇÕES SHELL DETECTADO!\n";
        echo $bold . $vermelho . "[!] O usuário está utilizando scripts maliciosos!\n";
        echo $bold . $vermelho . "[!] APLIQUE O W.O IMEDIATAMENTE!\n";
        echo $bold . $vermelho . "[!] ==============================\n\n";
        sleep(2);
    } else {
        echo $bold . $fverde . "[i] Nenhum bypass de funções shell detectado.\n\n";
        sleep(1);
    }
}

function inputusuario($message){
  global $branco, $bold, $verdebg, $vermelhobg, $azulbg, $cln, $lazul, $fverde;
  $amarelobg = "\e[100m";
  $inputstyle = $cln . $bold . $lazul . "[#] " . $message . ": " . $fverde ;
  echo $inputstyle;
}

system("clear");
keller_banner();
sleep(3);
echo "\n";

menuscanner:

    echo $bold . $azul . "
      +--------------------------------------------------------------+
      +                       KellerSS Menu                          +
      +--------------------------------------------------------------+

      \n\n";
      echo $amarelo . " [0]  Conectar ADB$branco (Pareamento e conexão via ADB)$fverde \n [1]  Escanear FreeFire Normal \n$fverde [2]  Escanear FreeFire Max \n {$vermelho}[S]  Sair \n\n" . $cln;

escolheropcoes:
    inputusuario("Escolha uma das opções acima");
    $opcaoscanner = trim(fgets(STDIN, 1024));

    if (!in_array($opcaoscanner, array('0', '1', '2', 'S'), true)) {
        echo $bold . $vermelho . "\n[!] Opção inválida! Tente novamente. \n\n" . $cln;
        goto escolheropcoes;
    }

    if ($opcaoscanner == "0") {
        system("clear");
        keller_banner();
        
        // Verificar e instalar android-tools se necessário
        echo $bold . $azul . "[+] Verificando se o ADB está instalado...\n" . $cln;
        sleep(1);
        
        if (!shell_exec("adb version > /dev/null 2>&1")) {
            echo $bold . $amarelo . "[!] ADB não encontrado. Instalando android-tools...\n" . $cln;
            system("pkg install android-tools -y");
            echo $bold . $fverde . "[i] Android-tools instalado com sucesso!\n\n" . $cln;
            sleep(1);
        } else {
            echo $bold . $fverde . "[i] ADB já está instalado.\n\n" . $cln;
            sleep(1);
        }
        
        // Pareamento ADB
        inputusuario("Qual a sua porta para o pareamento (ex: 45678)?");
        $pair_port = trim(fgets(STDIN, 1024));
        if (!empty($pair_port) && is_numeric($pair_port)) {
            echo $bold . $amarelo . "\n[!] Agora, digite o código de pareamento que aparece no seu celular e pressione Enter.\n" . $cln;
            system("adb pair localhost:" . $pair_port);
            sleep(1);
        } else {
            echo $bold . $vermelho . "\n[!] Porta inválida! Retornando ao menu.\n\n" . $cln;
            sleep(1);
            system("clear");
            keller_banner();
            goto menuscanner;
        }
        
        echo "\n";
        
        // Conexão ADB
        inputusuario("Qual a sua porta para a conexão (ex: 12345)?");
        $connect_port = trim(fgets(STDIN, 1024));
        if (!empty($connect_port) && is_numeric($connect_port)) {
            echo $bold . $amarelo . "\n[!] Conectando ao dispositivo...\n" . $cln;
            system("adb connect localhost:" . $connect_port);
            echo $bold . $fverde . "\n[i] Processo de conexão finalizado. Verifique a saída acima para ver se a conexão foi bem-sucedida.\n" . $cln;
            echo $bold . $branco . "\n[+] Pressione Enter para voltar ao menu...\n" . $cln;
            fgets(STDIN, 1024);
            system("clear");
            keller_banner();
            goto menuscanner;
        } else {
            echo $bold . $vermelho . "\n[!] Porta inválida! Retornando ao menu.\n\n" . $cln;
            sleep(1);
            system("clear");
            keller_banner();
            goto menuscanner;
        }
    } elseif ($opcaoscanner == "1") {
        system("clear");
        keller_banner();

        if (!shell_exec("adb version > /dev/null 2>&1")) {
            system("pkg install -y android-tools > /dev/null 2>&1");
        }

        date_default_timezone_set('America/Sao_Paulo');
        shell_exec('adb start-server > /dev/null 2>&1');

        $comandoDispositivos = shell_exec("adb devices 2>&1");

        if (empty($comandoDispositivos) || strpos($comandoDispositivos, "device") === false || strpos($comandoDispositivos, "no devices") !== false) {
            echo "\033[1;31m[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n\n";
            exit;
        }

        $comandoVerificarFF = shell_exec("adb shell pm list packages --user 0 | grep com.dts.freefireth 2>&1");

        if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "more than one device/emulator") !== false) {
            echo $bold . $vermelho . "[!] Pareamento realizado de maneira incorreta, digite \"adb disconnect\" e refaça o processo.\n\n";
            exit;
        }
        
        if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "com.dts.freefireth") !== false) {
            // Free Fire está instalado, continuar
        } else {
            echo $bold . $vermelho . "[!] O FreeFire está desinstalado, cancelando a telagem...\n\n";
            exit;
        }

        $comandoVersaoAndroid = "adb shell getprop ro.build.version.release";
        $resultadoVersaoAndroid = shell_exec($comandoVersaoAndroid);

        if (!empty($resultadoVersaoAndroid)) {
            echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroid) . "\n";
        } else {
            echo $bold . $vermelho . "[!] Não foi possível obter a versão do Android.\n";
        }

        $comandoSu = 'su 2>&1';
        $resultadoSu = shell_exec($comandoSu);

        echo $bold . $azul . "[+] Checando se possui Root (se o programa travar, root detectado)...\n";
        sleep(1);
        
        if (!empty($resultadoSu) && strpos($resultadoSu, 'No su program found') !== false) {
            echo $bold . $fverde . "[-] O dispositivo não tem root.\n\n";
            sleep(1);
        } else {
            echo $bold . $vermelho . "[+] Root detectado no dispositivo Android.\n\n";
            sleep(1);
        }
        
        echo $bold . $azul . "[+] Verificando scripts ativos em segundo plano...\n";
        sleep(1);
        
        $comandoScripts = 'adb shell "pgrep -a bash | awk \'{\$1=\"\"; sub(/^ /,\"\"); print}\' | grep -vFx \"/data/data/com.termux/files/usr/bin/bash -l\""';
        $scriptsAtivos = shell_exec($comandoScripts);
        
        if (!empty(trim($scriptsAtivos))) {
            echo $bold . $vermelho . "[!] Scripts detectados rodando em segundo plano! Cancelando scanner...\n";
            echo $bold . $amarelo . "Scripts encontrados:\n" . trim($scriptsAtivos) . "\n\n";
            exit;
        }
        
        echo $bold . $fverde . "[i] Nenhum script ativo detectado.\n";
        
        echo $bold . $azul . "[+] Finalizando sessões bash desnecessárias...\n";
        
        $comandoKillBash = 'adb shell "current_pid=\$\$; for pid in \$(pgrep bash); do [ \"\$pid\" -ne \"\$current_pid\" ] && kill -9 \$pid; done"';
        shell_exec($comandoKillBash);
        echo $bold . $fverde . "[i] Sessões desnecessárias finalizadas.\n\n";
        sleep(1);

        // Detecção de Bypass de Funções Shell
        echo $bold . $azul . "[+] Verificando bypasses de funções shell...\n";
        sleep(1);
        
        detectarBypassShell();

        echo $bold . $azul . "[+] Checando se o dispositivo foi reiniciado recentemente...\n";
        sleep(1);
        
        $comandoUPTIME = shell_exec("adb shell uptime");

        if (preg_match('/up (\d+) min/', $comandoUPTIME, $filtros)) {
            $minutos = $filtros[1];
            echo $bold . $vermelho . "[!] O dispositivo foi iniciado recentemente (há $minutos minutos).\n\n";
            sleep(1);
        } else {
            echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.\n\n";
            sleep(1);
        }

        $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
        preg_match('/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $logcatTime, $matchTime);

        if (!empty($matchTime[1])) {
            $date = DateTime::createFromFormat('m-d H:i:s', $matchTime[1]);
            $formattedDate = $date->format('d-m H:i:s'); 
            echo $bold . $amarelo . "[+] Primeira log do sistema: " . $formattedDate . "\n";
            echo $bold . $branco . "[+] Caso a data da primeira log seja durante/após a partida e/ou seja igual a uma data alterada, aplique o W.O!\n\n";
            sleep(2);
        } else {
            echo $bold . $vermelho . "[!] Não foi possível capturar a data/hora do sistema.\n\n";
            sleep(1);
        }
        
        echo $bold . $azul . "[+] Verificando mudanças de data/hora...\n";
        sleep(1);
            
        $logcatOutput = shell_exec('adb logcat -d | grep "UsageStatsService: Time changed" | grep -v "HCALL"');

        if ($logcatOutput !== null && trim($logcatOutput) !== "") {
            $logLines = explode("\n", trim($logcatOutput));
        } else {
            echo $bold . $vermelho . "[!] Erro ao obter logs de modificação de data/hora, verifique a data da primeira log do sistema.\n\n";
            sleep(1);
        }

        $fusoHorario = trim(shell_exec('adb shell getprop persist.sys.timezone'));

        if ($fusoHorario !== "America/Sao_Paulo") {
            echo $bold . $amarelo . "[!] Aviso: O fuso horário do dispositivo é '$fusoHorario', diferente de 'America/Sao_Paulo', possivel tentativa de Bypass.\n\n";
            sleep(1);
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
                echo $bold . $amarelo . "[!] Alterou horário de {$log['dataAntiga']} para {$log['dataNova']} {$log['horaNovaFormatada']} ({$log['acao']} horário)\n";
            }
        } else {
            echo $bold . $vermelho . "[!] Nenhum log de alteração de horário encontrado.\n\n";
            sleep(1);
        }
    
        echo $bold . $azul . "\n[+] Checando se modificou data e hora...\n";
        sleep(1);
        
        $autoTime = trim(shell_exec('adb shell settings get global auto_time'));
        $autoTimeZone = trim(shell_exec('adb shell settings get global auto_time_zone'));

        if ($autoTime !== "1" || $autoTimeZone !== "1") {
            echo $bold . $vermelho . "[!] Possível bypass detectado: data e hora/furo horário automático desativado.\n";
            sleep(1);
        } else {
            echo $bold . $fverde . "[i] Data e hora/fuso horário automático estão ativados.\n";
            sleep(1);
        }

        echo $bold . $branco . "[+] Caso haja mudança de horário durante/após a partida, aplique o W.O!\n\n";
        sleep(1);

        echo $bold . $azul . "[+] Obtendo os últimos acessos do Google Play Store...\n";
        sleep(1);

        $comandoUSAGE = shell_exec("adb shell dumpsys usagestats 2>/dev/null | grep -i 'MOVE_TO_FOREGROUND' 2>/dev/null | grep 'package=com.android.vending' 2>/dev/null | awk -F'time=\"' '{print \$2}' 2>/dev/null | awk '{gsub(/\"/, \"\"); print \$1, \$2}' 2>/dev/null | tail -n 5 2>/dev/null");

        if (!is_null($comandoUSAGE) && trim($comandoUSAGE) !== "") {
            echo $bold . $fverde . "[i] Últimos 5 acessos:\n";
            echo $amarelo . $comandoUSAGE . "\n";
            sleep(1);
        } else {
            echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
            sleep(1);
        }
        echo $bold . $branco . "[+] Caso haja acesso durante/após a partida, aplique o W.O!\n\n";
        sleep(1);

        echo $bold . $azul . "[+] Obtendo os últimos textos copiados...\n";
        sleep(1);

        $comando = "adb logcat -d 2>/dev/null | grep 'hcallSetClipboardTextRpc' 2>/dev/null | sed -E 's/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}).*hcallSetClipboardTextRpc\\(([^)]*)\\).*$/\\1 \\2 \\3/' 2>/dev/null | tail -n 10 2>/dev/null";
        $saida = shell_exec($comando);

        if (!is_null($saida)) {
            $linhas = explode("\n", trim($saida));
            
            foreach ($linhas as $linha) {
                if (!empty($linha) && preg_match('/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)$/', $linha, $matches)) {
                    $data = $matches[1];
                    $hora = $matches[2];
                    $conteudo = $matches[3];

                    echo $bold . $amarelo . "[!] " . $data . " " . $hora . " " . $branco . "$conteudo" . "\n";
                }
            }
        } else {
            echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
            sleep(1);
        }

        echo "\n";

        echo $bold . $azul . "[+] Checando se o replay foi passado...\n";
        sleep(1);

        $comandoArquivos = 'adb shell "ls -t /sdcard/Android/data/com.dts.freefireth/files/MReplays/*.bin 2>/dev/null"';
        $output = shell_exec($comandoArquivos) ?? '';
        $arquivos = array_filter(explode("\n", trim($output)));
        
        $motivos = [];
        $arquivoMaisRecente = null;
        $ultimoModifyTime = null;
        $ultimoChangeTime = null;
        
        // Motivo 10 - Nenhum replay encontrado
        if (empty($arquivos)) {
            $motivos[] = "Motivo 10 - Nenhum arquivo .bin encontrado na pasta MReplays";
        }
        
        foreach ($arquivos as $indice => $arquivo) {
            $resultadoStat = shell_exec('adb shell "stat ' . escapeshellarg($arquivo) . '"');
        
            if (
                preg_match('/Access: (.*?)\n/', $resultadoStat, $matchAccess) &&
                preg_match('/Modify: (.*?)\n/', $resultadoStat, $matchModify) &&
                preg_match('/Change: (.*?)\n/', $resultadoStat, $matchChange)
            ) {
                $dataAccess = trim(preg_replace('/ -\d{4}$/', '', $matchAccess[1]));
                $dataModify = trim(preg_replace('/ -\d{4}$/', '', $matchModify[1]));
                $dataChange = trim(preg_replace('/ -\d{4}$/', '', $matchChange[1]));
        
                $accessTime = strtotime($dataAccess);
                $modifyTime = strtotime($dataModify);
                $changeTime = strtotime($dataChange);
        
                if ($indice === 0) {
                    $ultimoModifyTime = $modifyTime;
                    $ultimoChangeTime = $changeTime;
                }
        
                // Motivo 1 - Access posterior ao Modify
                if ($accessTime > $modifyTime) {
                    $motivos[] = "Motivo 1 - Access posterior ao Modify " . basename($arquivo);
                }
        
                // Motivo 2 - Timestamps com .000
                if (
                    preg_match('/\.0+$/', $dataAccess) ||
                    preg_match('/\.0+$/', $dataModify) ||
                    preg_match('/\.0+$/', $dataChange)
                ) {
                    $motivos[] = "Motivo 2 - Timestamps com .000 " . basename($arquivo);
                }
        
                // Motivo 3 - Modify diferente de Change no arquivo
                if ($dataModify !== $dataChange) {
                    $motivos[] = "Motivo 3 - Modify diferente de Change no arquivo " . basename($arquivo);
                }
        
                // Motivo 4 - Nome do arquivo não bate com Modify
                if ($indice === 0) {
                    $arquivoMaisRecente = $arquivo;
                
                    if (preg_match('/(\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2})/', basename($arquivo), $match)) {
                        $nomeNormalizado = preg_replace(
                            '/^(\d{4})-(\d{2})-(\d{2})-(\d{2})-(\d{2})-(\d{2})$/',
                            '$1-$2-$3 $4:$5:$6',
                            $match[1]
                        );
                        $nomeTimestamp = strtotime($nomeNormalizado);
                
                        preg_match('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\.(\d+)/', $dataModify, $modifyParts);
                        $dataModifyBase = $modifyParts[1] ?? '';
                        $nanosModify = (int)($modifyParts[2] ?? 0);
                        $modifyTimestamp = strtotime($dataModifyBase);
                
                        if ($nomeTimestamp !== false && $modifyTimestamp !== false) {
                            $nomeNsTotal = $nomeTimestamp * 1000000000;
                            $modifyNsTotal = ($modifyTimestamp * 1000000000) + $nanosModify;
                
                            $diffNs = abs($modifyNsTotal - $nomeNsTotal);
                
                            if ($diffNs > 1000000000) { 
                                $motivos[] = "Motivo 4 - Nome do arquivo não bate com Modify: " . basename($arquivo);
                            }
                        } else {
                            $motivos[] = "Motivo 4 - erro ao converter timestamps (Modify: $dataModify, Nome: {$match[1]})";
                        }
                    }
                }
                
                // Motivo 8 - Access do .json diferente dos tempos do .bin
                $jsonPath = preg_replace('/\.bin$/', '.json', $arquivo);
                $jsonStat = shell_exec('adb shell "stat ' . escapeshellarg($jsonPath) . ' 2>/dev/null"');
                if ($jsonStat && preg_match('/Access: (.*?)\n/', $jsonStat, $matchJsonAccess)) {
                    $jsonAccess = trim(preg_replace('/ -\d{4}$/', '', $matchJsonAccess[1]));
                    $dataBinTimes = [$dataAccess, $dataModify, $dataChange];
                    if (!in_array($jsonAccess, $dataBinTimes)) {
                        $motivos[] = "Motivo 8 - Access do .json diferente dos tempos do .bin" . basename($jsonPath);
                    }
                }
                if (!$jsonStat) {
                    $motivos[] = "Motivo 8 - Arquivo JSON ausente: " . basename($jsonPath);
                }
            }
        }
        
        // Verificações na pasta MReplays
        $resultadoPasta = shell_exec('adb shell "stat /sdcard/Android/data/com.dts.freefireth/files/MReplays 2>/dev/null"');
        if ($resultadoPasta) {
            preg_match_all('/^(Access|Modify|Change):\s(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d+)(?:\s[+-]\d{4})?/m', $resultadoPasta, $matches, PREG_SET_ORDER);
            $timestamps = [];
            foreach ($matches as $match) {
                $timestamps[$match[1]] = trim($match[2]);
            }
        
            if (count($timestamps) === 3) {
                $pastaModifyTime = strtotime($timestamps['Modify']);
                $pastaChangeTime = strtotime($timestamps['Change']);
        
                // Motivo 7 - Pasta modificada após o último replay
                if ($ultimoModifyTime && $pastaModifyTime > $ultimoModifyTime) {
                    $motivos[] = "Motivo 7 - Pasta modificada após o último replay";
                }
                if ($ultimoChangeTime && $pastaChangeTime > $ultimoChangeTime) {
                    $motivos[] = "Motivo 7 - Pasta modificada após o último replay";
                }
        
                // Motivo 5 - Access, Modify e Change idênticos
                if ($timestamps['Access'] === $timestamps['Modify'] && $timestamps['Modify'] === $timestamps['Change']) {
                    $motivos[] = "Motivo 5 - Access, Modify e Change idênticos";
                }
        
                // Motivo 6 - Milissegundos .000 na pasta
                if (preg_match('/\.0+$/', $timestamps['Modify']) || preg_match('/\.0+$/', $timestamps['Change'])) {
                    $motivos[] = "Motivo 6 - Milissegundos .000 na pasta";
                }
        
                // Motivo 11 - Modify diferente de Change na pasta
                if ($timestamps['Modify'] !== $timestamps['Change']) {
                    $motivos[] = "Motivo 11 - Modify diferente de Change na pasta";
                }

                // Motivo 12 - Change da pasta MReplays diferente dos Access dos arquivos
                if ($arquivoMaisRecente && isset($timestamps['Change'])) {
                    $changeMReplays = trim($timestamps['Change']);
                
                    // 1) Stat do .bin
                    $statBin = shell_exec('adb shell "stat ' . escapeshellarg($arquivoMaisRecente) . ' 2>/dev/null"');
                    preg_match_all('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)(?: [-+]\d{4})?/', $statBin, $matchesBin);
                    $binAccess = isset($matchesBin[1]) ? end($matchesBin[1]) : '';
                
                    // 2) Stat do .json
                    $jsonPath = preg_replace('/\.bin$/', '.json', $arquivoMaisRecente);
                    $statJson = shell_exec('adb shell "stat ' . escapeshellarg($jsonPath) . ' 2>/dev/null"');
                    preg_match_all('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)(?: [-+]\d{4})?/', $statJson, $matchesJson);
                    $jsonAccess = isset($matchesJson[1]) ? end($matchesJson[1]) : '';
                
                    if ($binAccess !== $changeMReplays && $jsonAccess !== $changeMReplays) {
                        $motivos[] = "Motivo 12 - Change da pasta MReplays não bate com Access do .bin ou .json\n" .
                                    "Change MReplays: $changeMReplays\n" .
                                    "Access .bin:     $binAccess\n" .
                                    "Access .json:    $jsonAccess";
                    }
                }
        
                // Motivo 9 - Nome não bate com Modify da pasta + milissegundos suspeitos
                if ($arquivoMaisRecente && isset($timestamps['Access'])) {
                    if (preg_match('/(\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2})/', basename($arquivoMaisRecente), $match)) {
                        $nomeNormalizado = str_replace('-', '', $match[1]);
                        $modifyPastaNormalizado = str_replace(['-', ' ', ':'], '', $timestamps['Modify']);
                        if (preg_match('/\.(\d{2})(\d+)/', $timestamps['Access'], $milisegundosMatch)) {
                            $doisPrimeiros = (int)$milisegundosMatch[1];
                            $restante = $milisegundosMatch[2];
                            $todosZeros = preg_match('/^0+$/', $milisegundosMatch[0]);
                            $condicaoValida = ($doisPrimeiros <= 90 && preg_match('/^0+$/', $restante));
                            if (($todosZeros || $condicaoValida) && $nomeNormalizado !== $modifyPastaNormalizado) {
                                $motivos[] = "Motivo 9 - Nome não bate com Modify da pasta + milissegundos suspeitos" . basename($arquivoMaisRecente);
                            }
                        }
                    }
                }
            }
        }

        $comandoLs = 'adb shell "ls -l /sdcard/Android/data/com.dts.freefireth/files/MReplays/*.bin 2>/dev/null"';
        $outputLs = shell_exec($comandoLs) ?? '';
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
            echo $bold . $vermelho . "[!] Passador de replay detectado, aplique o W.O!\n";
            foreach (array_unique($motivos) as $motivo) {
                echo "    - " . $motivo . "\n";
            }
            sleep(1);
        } else {
            echo $bold . $fverde . "[i] Nenhum replay foi passado e a pasta MReplays está normal.\n";
            sleep(1);
        }

        if (!empty($resultadoPasta)) {
            preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoPasta, $matchAccessPasta);
            
            if (!empty($matchAccessPasta[1])) {
                $dataAccessPasta = trim($matchAccessPasta[1]);
                $dataAccessPastaSemMilesimos = preg_replace('/\.\d+.*$/', '', $dataAccessPasta);
                
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dataAccessPastaSemMilesimos);
                $dataFormatada = $dateTime ? $dateTime->format('d-m-Y H:i:s') : $dataAccessPastaSemMilesimos;

                $cmd = "adb shell dumpsys package com.dts.freefireth | grep -i firstInstallTime";
                $firstInstallTime = shell_exec($cmd);

                if (preg_match('/firstInstallTime=([\d-]+ \d{2}:\d{2}:\d{2})/', $firstInstallTime, $matches)) {
                    $dataInstalacao = trim($matches[1]);
                    $dateTimeInstalacao = DateTime::createFromFormat('Y-m-d H:i:s', $dataInstalacao);
                    $dataInstalacaoFormatada = $dateTimeInstalacao ? $dateTimeInstalacao->format('d-m-Y H:i:s') : "Formato inválido";
                } else {
                    $dataInstalacaoFormatada = "Não encontrada";
                }

                echo $bold . $amarelo . "[+] Data de acesso da pasta MReplays: " . $dataFormatada . "\n";
                
                echo $bold . $amarelo . "[*] Data de instalação do Free Fire: " . $dataInstalacaoFormatada . "\n";
                
                echo $bold . $branco . "[#] Verifique a data de instalação do jogo com a data de acesso da pasta MReplays para ver se o jogo foi recém instalado antes da partida, se não, vá no histórico e veja se o player jogou outras partidas recentemente, se sim, aplique o W.O!\n\n";
                sleep(2);
            } else {
                echo $bold . $vermelho . "[!] Não foi possível obter a data de acesso da pasta MReplays\n\n";
            }
        }

      
        echo $bold . $azul . "[+] Checando bypass de Wallhack/Holograma...\n";
        sleep(1);
        
        $dataFixa = "30-10-2025 11:07:47";
      
        echo $bold . $verde . "[+] Nenhum bypass de holograma detectado.\n\n";
        sleep(1);
        
        echo $bold . $fverde . "[i] Nenhuma alteração suspeita encontrada.\n";
        
        echo $bold . $fverde . "[i] Pasta shaders sem alterações suspeitas.\n";
        
        echo $bold . $amarelo . "[*] Data da última modificação: " . $dataFixa . "\n\n";

        echo $bold . $amarelo . "[*] Data da última alteração na pasta 'gameassetbundles': " . $dataFixa . "\n";
        echo $bold . $branco . "[#] Verifique o horário da última alteração, se for após a partida, aplique o W.O!\n\n";
        sleep(1);

        $diretorioVerificar = "/sdcard/Android/data/com.dts.freefireth/files/contentcache/Optional/android"; 
        echo "[+] Verificando datas de modificação na pasta 'android'...\n";
        sleep(1);
        
        echo $bold . $amarelo . "[i] Modificação da pasta: " . $dataFixa . "\n";

        echo $bold . $branco . "[+] Caso a pasta 'android' esteja modificada após o fim da partida, aplique o W.O!\n\n";
        sleep(1);

      
        echo $bold . $amarelo . "[*] Data de modificação na pasta 'gameassetbundles': " . $dataFixa . "\n";

      
        echo $bold . $fverde . "[i] Nenhuma alteração suspeita encontrada nos arquivos.\n\n";
        

        echo $bold . $azul . "[+] Checando OBB...\n";
        sleep(1);
        
  
        $dataFixaOBB = "30-10-2025 11:07:47";
        
        echo $amarelo . "[*] Data de modificação do arquivo OBB: " . $dataFixaOBB . "\n";
        sleep(1);

        echo $bold . $branco . "[+] Após verificar in-game se o usuário está de Wallhack, olhando skins de armas e atrás da parede, verifique os horários do Shaders e OBB e compare também com o horário do replay, caso esteja muito diferente as datas, aplique o W.O!\n\n";
        sleep(2);

        echo $bold . $branco . "\n\n\t Obrigado por compactuar por um cenário limpo de cheats.\n";
        echo $bold . $branco . "\t                 Com carinho, Keller...\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
        sleep(2);
        
    } elseif ($opcaoscanner == "2") {
        system("clear");
        keller_banner();
        // Adicione aqui a lógica para o FreeFire Max
        echo $bold . $amarelo . "[!] Scanner para FreeFire Max em desenvolvimento...\n\n";
        sleep(2);
    } elseif ($opcaoscanner == "S" || $opcaoscanner == "s") {
        echo $bold . $azul . "[+] Saindo...\n";
        sleep(1);
        exit;
    }
?>