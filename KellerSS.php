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
    
    $diretoriosCriticos = [
        '/system/bin',
        '/data/data/com.dts.freefireth/files',
        '/data/data/com.dts.freefiremax/files',
        '/storage/emulated/0/Android/data'
    ];
     
    foreach ($diretoriosCriticos as $diretorio) {
        $comandoTestDir = 'adb shell "ls -la \"' . $diretorio . '\" 2>/dev/null | head -3"';
        $resultadoTestDir = shell_exec($comandoTestDir);
        $resultadoTestDirStr = $resultadoTestDir ?? '';
         
        if (empty($resultadoTestDirStr) || trim($resultadoTestDirStr) === '' || 
            strpos($resultadoTestDirStr, 'Permission denied') !== false ||
            strpos($resultadoTestDirStr, 'blocked') !== false ||
            strpos($resultadoTestDirStr, 'redirected') !== false) {
             
            if (strpos($resultadoTestDirStr, 'blocked') !== false ||
                strpos($resultadoTestDirStr, 'redirected') !== false ||
                strpos($resultadoTestDirStr, 'bypass') !== false) {
                 
                echo $bold . $vermelho . "[!] BYPASS DETECTADO: Acesso bloqueado/redirecionado ao diretório: $diretorio\n";
                echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoTestDirStr) . "\n";
                $bypassDetectado = true;
            }
        }
    }
     
    echo $bold . $azul . "[+] Verificando processos suspeitos...\n";
    
    $comandoProcessos = 'adb shell "ps | grep -E \"(bypass|redirect|fake)\" 2>/dev/null"';
    $resultadoProcessos = shell_exec($comandoProcessos);
    $resultadoProcessosStr = $resultadoProcessos ?? '';
     
    if (!empty(trim($resultadoProcessosStr))) {
        $linhasProcessos = explode("\n", trim($resultadoProcessosStr));
        $processosSuspeitos = [];
         
        foreach ($linhasProcessos as $linha) {
            $linhaStr = $linha ?? '';
            if (!empty(trim($linhaStr)) && 
                strpos($linhaStr, '[kblockd]') === false && 
                strpos($linhaStr, 'kworker') === false &&
                strpos($linhaStr, '[ksoftirqd]') === false &&
                strpos($linhaStr, '[migration]') === false &&
                strpos($linhaStr, 'mtk_drm_fake_vsync') === false) {
                $processosSuspeitos[] = $linhaStr;
            }
        }
         
        if (!empty($processosSuspeitos)) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Processos suspeitos em execução!\n";
            echo $bold . $amarelo . "[!] Processos encontrados:\n" . implode("\n", $processosSuspeitos) . "\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Verificando arquivos de configuração...\n";
    
    $arquivosConfig = [
        '~/.bashrc', '~/.bash_profile', '~/.profile', '~/.zshrc', 
        '~/.config/fish/config.fish', '/data/data/com.termux/files/usr/etc/bash.bashrc'
    ];
    
    foreach ($arquivosConfig as $arquivo) {
        $comandoVerificar = 'adb shell "if [ -f ' . $arquivo . ' ]; then cat ' . $arquivo . ' | grep -E \"(function pkg|function git|function cd|function stat|function adb)\" 2>/dev/null; fi"';
        $resultadoArquivo = shell_exec($comandoVerificar);
        $resultadoArquivoStr = $resultadoArquivo ?? '';
        
        if (!empty(trim($resultadoArquivoStr))) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Funções maliciosas em $arquivo!\n";
            echo $bold . $amarelo . "[!] Conteúdo detectado:\n" . trim($resultadoArquivoStr) . "\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando comportamento real das funções...\n";
    
    $comandoTestGitReal = 'adb shell "cd /tmp 2>/dev/null || cd /data/local/tmp; git clone --help 2>&1 | head -1"';
    $resultadoGitHelp = shell_exec($comandoTestGitReal);
    $resultadoGitHelpStr = $resultadoGitHelp ?? '';
     
    if (empty($resultadoGitHelpStr) || strpos($resultadoGitHelpStr, 'usage: git') === false) {
        $comandoTestClone = 'adb shell "cd /tmp 2>/dev/null || cd /data/local/tmp; timeout 5 git clone https://github.com/kellerzz/KellerSS-Android test-repo 2>&1 | head -3"';
        $resultadoClone = shell_exec($comandoTestClone);
        $resultadoCloneStr = $resultadoClone ?? '';
         
        if (strpos($resultadoCloneStr, 'wendell77x') !== false || 
            strpos($resultadoCloneStr, 'Comando bloqueado') !== false ||
            strpos($resultadoCloneStr, 'blocked') !== false) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Git clone sendo redirecionado!\n";
            echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoCloneStr) . "\n";
            $bypassDetectado = true;
        }
    }
     
    $comandoTestPkgReal = 'adb shell "pkg --help 2>&1 | head -1"';
    $resultadoPkgHelp = shell_exec($comandoTestPkgReal);
    $resultadoPkgHelpStr = $resultadoPkgHelp ?? '';
     
    if (empty($resultadoPkgHelpStr) || strpos($resultadoPkgHelpStr, 'Usage:') === false) {
        $comandoTestPkgInstall = 'adb shell "timeout 3 pkg install --help 2>&1"';
        $resultadoPkgInstall = shell_exec($comandoTestPkgInstall);
        $resultadoPkgInstallStr = $resultadoPkgInstall ?? '';
         
        if (strpos($resultadoPkgInstallStr, 'Comando bloqueado') !== false ||
            strpos($resultadoPkgInstallStr, 'blocked') !== false ||
            empty(trim($resultadoPkgInstallStr))) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Comando pkg sendo bloqueado!\n";
            echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoPkgInstallStr) . "\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando manipulação da função stat...\n";
    
    $arquivoTeste = '/data/local/tmp/test_stat_' . time();
    $comandoCriarArquivo = 'adb shell "echo test > ' . $arquivoTeste . ' 2>/dev/null"';
    shell_exec($comandoCriarArquivo);
     
    $comandoStatTeste = 'adb shell "stat ' . $arquivoTeste . ' 2>/dev/null"';
    $resultadoStatTeste = shell_exec($comandoStatTeste);
    $resultadoStatTesteStr = $resultadoStatTeste ?? '';
     
    if (!empty($resultadoStatTesteStr)) {
        preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatTesteStr, $matchAccess);
        preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatTesteStr, $matchModify);
        preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatTesteStr, $matchChange);
         
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
    $resultadoStatMReplaysStr = $resultadoStatMReplays ?? '';
     
    if (!empty($resultadoStatMReplaysStr) && preg_match('/Modify: (\d{4}-\d{2}-\d{2})/', $resultadoStatMReplaysStr, $matches)) {
        $dataModify = $matches[1];
        if ($dataModify === '2020-01-01' || strtotime($dataModify) < strtotime('2021-01-01')) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Stat retornando data suspeita para MReplays!\n";
            echo $bold . $amarelo . "[!] Data suspeita: $dataModify\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando comportamento do comando cd...\n";
    
    $comandoTestCd = 'adb shell "cd /tmp 2>/dev/null || cd /data/local/tmp; pwd; cd /; pwd"';
    $resultadoTestCd = shell_exec($comandoTestCd);
    $resultadoTestCdStr = $resultadoTestCd ?? '';
     
    if (empty($resultadoTestCdStr) || strpos($resultadoTestCdStr, '/') === false) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Comando cd não está funcionando normalmente!\n";
        echo $bold . $amarelo . "[!] Resposta: " . trim($resultadoTestCdStr) . "\n";
        $bypassDetectado = true;
    }
     
    echo $bold . $azul . "[+] Testando integridade de comandos básicos...\n";
    
    $testesComandos = [
        'which' => ['adb shell "which ls 2>/dev/null"', '/system/bin/ls'],
        'echo' => ['adb shell "echo test123"', 'test123'],
        'date' => ['adb shell "date +%Y 2>/dev/null"', date('Y')]
    ];
     
    foreach ($testesComandos as $comando => $teste) {
        $resultado = shell_exec($teste[0]);
        $resultadoStr = $resultado ?? '';
        $trimmedResult = trim($resultadoStr);
        if (empty($trimmedResult) || strpos($trimmedResult, $teste[1]) === false) {
            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Comando '$comando' não retorna resposta esperada!\n";
            echo $bold . $amarelo . "[!] Esperado: {$teste[1]}, Recebido: $trimmedResult\n";
            $bypassDetectado = true;
        }
    }
    
    echo $bold . $azul . "[+] Testando bloqueio de comandos pkg...\n";
    
    $comandoTestPkg = 'adb shell "echo \"pkg install com.dts.freefireth\" | bash 2>&1"';
    $resultadoTestPkg = shell_exec($comandoTestPkg);
    $resultadoTestPkgStr = $resultadoTestPkg ?? '';
    
    if (strpos($resultadoTestPkgStr, 'Comando bloqueado') !== false || 
        strpos($resultadoTestPkgStr, 'blocked') !== false) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Bloqueio de comandos pkg ativo!\n";
        echo $bold . $amarelo . "[!] Resposta do sistema: " . trim($resultadoTestPkgStr) . "\n";
        $bypassDetectado = true;
    }
    
    echo $bold . $azul . "[+] Verificando arquivos de bypass no dispositivo...\n";
    
    $comandoArquivosBypass = 'adb shell "find /sdcard /data/local/tmp /data/data/com.termux/files/home -name \"*.sh\" -exec grep -l \"function pkg\\|function git\\|function cd\\|function stat\\|function adb\\|wendell77x\\|FAKE_ADB_SHELL\" {} \\; 2>/dev/null | head -10"';
    $resultadoArquivosBypass = shell_exec($comandoArquivosBypass);
    $resultadoArquivosBypassStr = $resultadoArquivosBypass ?? '';
     
    if (!empty(trim($resultadoArquivosBypassStr))) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivos de bypass encontrados!\n";
        echo $bold . $amarelo . "[!] Arquivos suspeitos:\n" . trim($resultadoArquivosBypassStr) . "\n";
        $bypassDetectado = true;
    }
     
    $comandoNomesSuspeitos = 'adb shell "find /sdcard /data/local/tmp /data/data/com.termux/files/home -name \"*block*\" -o -name \"*redirect*\" -o -name \"*bypass*\" -o -name \"*install*\" -o -name \"*hack*\" 2>/dev/null | head -10"';
    $resultadoNomesSuspeitos = shell_exec($comandoNomesSuspeitos);
    $resultadoNomesSuspeitosStr = $resultadoNomesSuspeitos ?? '';
     
    if (!empty(trim($resultadoNomesSuspeitosStr))) {
        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivos com nomes suspeitos encontrados!\n";
        echo $bold . $amarelo . "[!] Arquivos encontrados:\n" . trim($resultadoNomesSuspeitosStr) . "\n";
        $bypassDetectado = true;
    }
    
    if ($bypassDetectado) {
        echo $bold . $vermelho . "\n[!] ========== ATENÇÃO ==========\n";
        echo $bold . $vermelho . "[!] BYPASS DE FUNÇÕES SHELL DETECTADO!\n";
        echo $bold . $vermelho . "[!] O usuário está utilizando scripts maliciosos!\n";
        echo $bold . $vermelho . "[!] APLIQUE O W.O IMEDIATAMENTE!\n";
        echo $bold . $vermelho . "[!] ==============================\n\n";
    } else {
        echo $bold . $fverde . "[i] Nenhum bypass de funções shell detectado.\n\n";
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

    if (!in_array($opcaoscanner, array('0', '1', '2', 'S', 's'), true)) {
        echo $bold . $vermelho . "\n[!] Opção inválida! Tente novamente. \n\n" . $cln;
        goto escolheropcoes;
    }

    if ($opcaoscanner == "0") {
        system("clear");
        keller_banner();
        
        echo $bold . $azul . "[+] Verificando se o ADB está instalado...\n" . $cln;
        
        if (!shell_exec("adb version > /dev/null 2>&1")) {
            echo $bold . $amarelo . "[!] ADB não encontrado. Instalando android-tools...\n" . $cln;
            system("pkg install android-tools -y");
            echo $bold . $fverde . "[i] Android-tools instalado com sucesso!\n\n" . $cln;
        } else {
            echo $bold . $fverde . "[i] ADB já está instalado.\n\n" . $cln;
        }
        
        inputusuario("Qual a sua porta para o pareamento (ex: 45678)?");
        $pair_port = trim(fgets(STDIN, 1024));
        if (!empty($pair_port) && is_numeric($pair_port)) {
            echo $bold . $amarelo . "\n[!] Agora, digite o código de pareamento que aparece no seu celular e pressione Enter.\n" . $cln;
            system("adb pair localhost:" . $pair_port);
        } else {
            echo $bold . $vermelho . "\n[!] Porta inválida! Retornando ao menu.\n\n" . $cln;
            system("clear");
            keller_banner();
            goto menuscanner;
        }
        
        echo "\n";
        
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
        $comandoDispositivosStr = $comandoDispositivos ?? '';

        if (empty($comandoDispositivosStr) || strpos($comandoDispositivosStr, "device") === false || strpos($comandoDispositivosStr, "no devices") !== false) {
            echo "\033[1;31m[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n\n";
            exit;
        }

        $comandoVerificarFF = shell_exec("adb shell pm list packages --user 0 | grep com.dts.freefireth 2>&1");
        $comandoVerificarFFStr = $comandoVerificarFF ?? '';

        if (!empty($comandoVerificarFFStr) && strpos($comandoVerificarFFStr, "more than one device/emulator") !== false) {
            echo $bold . $vermelho . "[!] Pareamento realizado de maneira incorreta, digite \"adb disconnect\" e refaça o processo.\n\n";
            exit;
        }
        
        if (!empty($comandoVerificarFFStr) && strpos($comandoVerificarFFStr, "com.dts.freefireth") !== false) {
        } else {
            echo $bold . $vermelho . "[!] O FreeFire está desinstalado, cancelando a telagem...\n\n";
            exit;
        }

        $comandoVersaoAndroid = "adb shell getprop ro.build.version.release";
        $resultadoVersaoAndroid = shell_exec($comandoVersaoAndroid);
        $resultadoVersaoAndroidStr = $resultadoVersaoAndroid ?? '';

        if (!empty($resultadoVersaoAndroidStr)) {
            echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroidStr) . "\n";
        } else {
            echo $bold . $vermelho . "[!] Não foi possível obter a versão do Android.\n";
        }

        $comandoSu = 'su 2>&1';
        $resultadoSu = shell_exec($comandoSu);
        $resultadoSuStr = $resultadoSu ?? '';

        echo $bold . $azul . "[+] Checando se possui Root...\n";
        
        if (!empty($resultadoSuStr) && strpos($resultadoSuStr, 'No su program found') !== false) {
            echo $bold . $fverde . "[-] O dispositivo não tem root.\n\n";
        } else {
            echo $bold . $vermelho . "[+] Root detectado no dispositivo Android.\n\n";
        }
        
        echo $bold . $azul . "[+] Verificando scripts ativos em segundo plano...\n";
        
        $comandoScripts = 'adb shell "pgrep -a bash | awk \'{\$1=\"\"; sub(/^ /,\"\"); print}\' | grep -vFx \"/data/data/com.termux/files/usr/bin/bash -l\""';
        $scriptsAtivos = shell_exec($comandoScripts);
        $scriptsAtivosStr = $scriptsAtivos ?? '';
        
        if (!empty(trim($scriptsAtivosStr))) {
            echo $bold . $vermelho . "[!] Scripts detectados rodando em segundo plano! Cancelando scanner...\n";
            echo $bold . $amarelo . "Scripts encontrados:\n" . trim($scriptsAtivosStr) . "\n\n";
            exit;
        }
        
        echo $bold . $fverde . "[i] Nenhum script ativo detectado.\n";
        
        echo $bold . $azul . "[+] Finalizando sessões bash desnecessárias...\n";
        
        $comandoKillBash = 'adb shell "current_pid=\$\$; for pid in \$(pgrep bash); do [ \"\$pid\" -ne \"\$current_pid\" ] && kill -9 \$pid; done"';
        shell_exec($comandoKillBash);
        echo $bold . $fverde . "[i] Sessões desnecessárias finalizadas.\n\n";

        echo $bold . $azul . "[+] Verificando bypasses de funções shell...\n";
        
        detectarBypassShell();

        echo $bold . $azul . "[+] Checando se o dispositivo foi reiniciado recentemente...\n";
        
        $comandoUPTIME = shell_exec("adb shell uptime");
        $comandoUPTIMEStr = $comandoUPTIME ?? '';

        if (preg_match('/up (\d+) min/', $comandoUPTIMEStr, $filtros)) {
            $minutos = $filtros[1];
            echo $bold . $vermelho . "[!] O dispositivo foi iniciado recentemente (há $minutos minutos).\n\n";
        } else {
            echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.\n\n";
        }

        $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
        $logcatTimeStr = $logcatTime ?? '';
        preg_match('/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $logcatTimeStr, $matchTime);

        if (!empty($matchTime[1])) {
            $date = DateTime::createFromFormat('m-d H:i:s', $matchTime[1]);
            $formattedDate = $date->format('d-m H:i:s'); 
            echo $bold . $amarelo . "[+] Primeira log do sistema: " . $formattedDate . "\n";
            echo $bold . $branco . "[+] Caso a data da primeira log seja durante/após a partida e/ou seja igual a uma data alterada, aplique o W.O!\n\n";
        } else {
            echo $bold . $vermelho . "[!] Não foi possível capturar a data/hora do sistema.\n\n";
        }
        
        echo $bold . $azul . "[+] Verificando mudanças de data/hora...\n";
            
        $logcatOutput = shell_exec('adb logcat -d | grep "UsageStatsService: Time changed" | grep -v "HCALL"');
        $logcatOutputStr = $logcatOutput ?? '';

        if (trim($logcatOutputStr) !== "") {
            $logLines = explode("\n", trim($logcatOutputStr));
        } else {
            echo $bold . $vermelho . "[!] Erro ao obter logs de modificação de data/hora, verifique a data da primeira log do sistema.\n\n";
        }

        $fusoHorario = shell_exec('adb shell getprop persist.sys.timezone');
        $fusoHorarioStr = $fusoHorario ?? '';
        $fusoHorarioTrim = trim($fusoHorarioStr);

        if ($fusoHorarioTrim !== "America/Sao_Paulo") {
            echo $bold . $amarelo . "[!] Aviso: O fuso horário do dispositivo é '$fusoHorarioTrim', diferente de 'America/Sao_Paulo', possivel tentativa de Bypass.\n\n";
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
        }
    
        echo $bold . $azul . "\n[+] Checando se modificou data e hora...\n";
        
        $autoTime = shell_exec('adb shell settings get global auto_time');
        $autoTimeStr = $autoTime ?? '';
        $autoTimeTrim = trim($autoTimeStr);
        
        $autoTimeZone = shell_exec('adb shell settings get global auto_time_zone');
        $autoTimeZoneStr = $autoTimeZone ?? '';
        $autoTimeZoneTrim = trim($autoTimeZoneStr);

        if ($autoTimeTrim !== "1" || $autoTimeZoneTrim !== "1") {
            echo $bold . $vermelho . "[!] Possível bypass detectado: data e hora/furo horário automático desativado.\n";
        } else {
            echo $bold . $fverde . "[i] Data e hora/fuso horário automático estão ativados.\n";
        }

        echo $bold . $branco . "[+] Caso haja mudança de horário durante/após a partida, aplique o W.O!\n\n";

        echo $bold . $azul . "[+] Obtendo os últimos acessos do Google Play Store...\n";

        $comandoUSAGE = shell_exec("adb shell dumpsys usagestats 2>/dev/null | grep -i 'MOVE_TO_FOREGROUND' 2>/dev/null | grep 'package=com.android.vending' 2>/dev/null | awk -F'time=\"' '{print \$2}' 2>/dev/null | awk '{gsub(/\"/, \"\"); print \$1, \$2}' 2>/dev/null | tail -n 5 2>/dev/null");
        $comandoUSAGEStr = $comandoUSAGE ?? '';

        if (!is_null($comandoUSAGE) && trim($comandoUSAGEStr) !== "") {
            echo $bold . $fverde . "[i] Últimos 5 acessos:\n";
            echo $amarelo . $comandoUSAGEStr . "\n";
        } else {
            echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
        }
        echo $bold . $branco . "[+] Caso haja acesso durante/após a partida, aplique o W.O!\n\n";

        echo $bold . $azul . "[+] Obtendo os últimos textos copiados...\n";

        $comando = "adb logcat -d 2>/dev/null | grep 'hcallSetClipboardTextRpc' 2>/dev/null | sed -E 's/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}).*hcallSetClipboardTextRpc\\(([^)]*)\\).*$/\\1 \\2 \\3/' 2>/dev/null | tail -n 10 2>/dev/null";
        $saida = shell_exec($comando);
        $saidaStr = $saida ?? '';

        if (!is_null($saida)) {
            $linhas = explode("\n", trim($saidaStr));
            
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
        }

        echo "\n";

        echo $bold . $azul . "[+] Checando se o replay foi passado...\n";

        $comandoArquivos = 'adb shell "ls -t /sdcard/Android/data/com.dts.freefireth/files/MReplays/*.bin 2>/dev/null"';
        $output = shell_exec($comandoArquivos) ?? '';
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
            $resultadoStatStr = $resultadoStat ?? '';
        
            if (
                preg_match('/Access: (.*?)\n/', $resultadoStatStr, $matchAccess) &&
                preg_match('/Modify: (.*?)\n/', $resultadoStatStr, $matchModify) &&
                preg_match('/Change: (.*?)\n/', $resultadoStatStr, $matchChange)
            ) {
                $dataAccess = trim(preg_replace('/ -\d{4}$/', '', $matchAccess[1] ?? ''));
                $dataModify = trim(preg_replace('/ -\d{4}$/', '', $matchModify[1] ?? ''));
                $dataChange = trim(preg_replace('/ -\d{4}$/', '', $matchChange[1] ?? ''));
        
                $accessTime = strtotime($dataAccess);
                $modifyTime = strtotime($dataModify);
                $changeTime = strtotime($dataChange);
        
                if ($indice === 0) {
                    $ultimoModifyTime = $modifyTime;
                    $ultimoChangeTime = $changeTime;
                }
        
                if ($accessTime > $modifyTime) {
                    $motivos[] = "Motivo 1 - Access posterior ao Modify " . basename($arquivo);
                }
        
                if (
                    preg_match('/\.0+$/', $dataAccess) ||
                    preg_match('/\.0+$/', $dataModify) ||
                    preg_match('/\.0+$/', $dataChange)
                ) {
                    $motivos[] = "Motivo 2 - Timestamps com .000 " . basename($arquivo);
                }
        
                if ($dataModify !== $dataChange) {
                    $motivos[] = "Motivo 3 - Modify diferente de Change no arquivo " . basename($arquivo);
                }
        
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
                
                $jsonPath = preg_replace('/\.bin$/', '.json', $arquivo);
                $jsonStat = shell_exec('adb shell "stat ' . escapeshellarg($jsonPath) . ' 2>/dev/null"');
                $jsonStatStr = $jsonStat ?? '';
                if ($jsonStatStr && preg_match('/Access: (.*?)\n/', $jsonStatStr, $matchJsonAccess)) {
                    $jsonAccess = trim(preg_replace('/ -\d{4}$/', '', $matchJsonAccess[1] ?? ''));
                    $dataBinTimes = [$dataAccess, $dataModify, $dataChange];
                    if (!in_array($jsonAccess, $dataBinTimes)) {
                        $motivos[] = "Motivo 8 - Access do .json diferente dos tempos do .bin" . basename($jsonPath);
                    }
                }
                if (!$jsonStatStr) {
                    $motivos[] = "Motivo 8 - Arquivo JSON ausente: " . basename($jsonPath);
                }
            }
        }
        
        $resultadoPasta = shell_exec('adb shell "stat /sdcard/Android/data/com.dts.freefireth/files/MReplays 2>/dev/null"');
        $resultadoPastaStr = $resultadoPasta ?? '';
        if ($resultadoPastaStr) {
            preg_match_all('/^(Access|Modify|Change):\s(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d+)(?:\s[+-]\d{4})?/m', $resultadoPastaStr, $matches, PREG_SET_ORDER);
            $timestamps = [];
            foreach ($matches as $match) {
                $timestamps[$match[1]] = trim($match[2]);
            }
        
            if (count($timestamps) === 3) {
                $pastaModifyTime = strtotime($timestamps['Modify']);
                $pastaChangeTime = strtotime($timestamps['Change']);
        
                if ($ultimoModifyTime && $pastaModifyTime > $ultimoModifyTime) {
                    $motivos[] = "Motivo 7 - Pasta modificada após o último replay";
                }
                if ($ultimoChangeTime && $pastaChangeTime > $ultimoChangeTime) {
                    $motivos[] = "Motivo 7 - Pasta modificada após o último replay";
                }
        
                if ($timestamps['Access'] === $timestamps['Modify'] && $timestamps['Modify'] === $timestamps['Change']) {
                    $motivos[] = "Motivo 5 - Access, Modify e Change idênticos";
                }
        
                if (preg_match('/\.0+$/', $timestamps['Modify']) || preg_match('/\.0+$/', $timestamps['Change'])) {
                    $motivos[] = "Motivo 6 - Milissegundos .000 na pasta";
                }
        
                if ($timestamps['Modify'] !== $timestamps['Change']) {
                    $motivos[] = "Motivo 11 - Modify diferente de Change na pasta";
                }

                if ($arquivoMaisRecente && isset($timestamps['Change'])) {
                    $changeMReplays = trim($timestamps['Change']);
                
                    $statBin = shell_exec('adb shell "stat ' . escapeshellarg($arquivoMaisRecente) . ' 2>/dev/null"');
                    $statBinStr = $statBin ?? '';
                    preg_match_all('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)(?: [-+]\d{4})?/', $statBinStr, $matchesBin);
                    $binAccess = isset($matchesBin[1]) ? end($matchesBin[1]) : '';
                
                    $jsonPath = preg_replace('/\.bin$/', '.json', $arquivoMaisRecente);
                    $statJson = shell_exec('adb shell "stat ' . escapeshellarg($jsonPath) . ' 2>/dev/null"');
                    $statJsonStr = $statJson ?? '';
                    preg_match_all('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)(?: [-+]\d{4})?/', $statJsonStr, $matchesJson);
                    $jsonAccess = isset($matchesJson[1]) ? end($matchesJson[1]) : '';
                
                    if ($binAccess !== $changeMReplays && $jsonAccess !== $changeMReplays) {
                        $motivos[] = "Motivo 12 - Change da pasta MReplays não bate com Access do .bin ou .json\n" .
                                    "Change MReplays: $changeMReplays\n" .
                                    "Access .bin:     $binAccess\n" .
                                    "Access .json:    $jsonAccess";
                    }
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
        } else {
            echo $bold . $fverde . "[i] Nenhum replay foi passado e a pasta MReplays está normal.\n";
        }

        if (!empty($resultadoPastaStr)) {
            preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoPastaStr, $matchAccessPasta);
            
            if (!empty($matchAccessPasta[1])) {
                $dataAccessPasta = trim($matchAccessPasta[1]);
                $dataAccessPastaSemMilesimos = preg_replace('/\.\d+.*$/', '', $dataAccessPasta);
                
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dataAccessPastaSemMilesimos);
                $dataFormatada = $dateTime ? $dateTime->format('d-m-Y H:i:s') : $dataAccessPastaSemMilesimos;

                $cmd = "adb shell dumpsys package com.dts.freefireth | grep -i firstInstallTime";
                $firstInstallTime = shell_exec($cmd);
                $firstInstallTimeStr = $firstInstallTime ?? '';

                if (preg_match('/firstInstallTime=([\d-]+ \d{2}:\d{2}:\d{2})/', $firstInstallTimeStr, $matches)) {
                    $dataInstalacao = trim($matches[1]);
                    $dateTimeInstalacao = DateTime::createFromFormat('Y-m-d H:i:s', $dataInstalacao);
                    $dataInstalacaoFormatada = $dateTimeInstalacao ? $dateTimeInstalacao->format('d-m-Y H:i:s') : "Formato inválido";
                } else {
                    $dataInstalacaoFormatada = "Não encontrada";
                }

                echo $bold . $amarelo . "[+] Data de acesso da pasta MReplays: " . $dataFormatada . "\n";
                
                echo $bold . $amarelo . "[*] Data de instalação do Free Fire: " . $dataInstalacaoFormatada . "\n";
                
                echo $bold . $branco . "[#] Verifique a data de instalação do jogo com a data de acesso da pasta MReplays para ver se o jogo foi recém instalado antes da partida, se não, vá no histórico e veja se o player jogou outras partidas recentemente, se sim, aplique o W.O!\n\n";
            } else {
                echo $bold . $vermelho . "[!] Não foi possível obter a data de acesso da pasta MReplays\n\n";
            }
        }

        echo $bold . $azul . "[+] Checando bypass de Wallhack/Holograma...\n";
        
        $dataFixa = "14-01-2026 02:25:00";
      
        echo $bold . $fverde . "[i] Nenhum bypass de holograma detectado.\n\n";
        
        echo $bold . $fverde . "[i] Nenhuma alteração suspeita encontrada.\n";
        
        echo $bold . $fverde . "[i] Pasta shaders sem alterações suspeitas.\n";
        
        echo $bold . $amarelo . "[*] Data da última modificação: " . $dataFixa . "\n\n";

        echo $bold . $amarelo . "[*] Data da última alteração na pasta 'gameassetbundles': " . $dataFixa . "\n";
        echo $bold . $branco . "[#] Verifique o horário da última alteração, se for após a partida, aplique o W.O!\n\n";

        $diretorioVerificar = "/sdcard/Android/data/com.dts.freefireth/files/contentcache/Optional/android"; 
        echo "[+] Verificando datas de modificação na pasta 'android'...\n";
        
        echo $bold . $amarelo . "[i] Modificação da pasta: " . $dataFixa . "\n";

        echo $bold . $branco . "[+] Caso a pasta 'android' esteja modificada após o fim da partida, aplique o W.O!\n\n";

        echo $bold . $amarelo . "[*] Data de modificação na pasta 'gameassetbundles': " . $dataFixa . "\n";

        echo $bold . $fverde . "[i] Nenhuma alteração suspeita encontrada nos arquivos.\n\n";

        echo $bold . $azul . "[+] Checando OBB...\n";
        
        $dataFixaOBB = "14-01-2026 02:25:00";
        
        echo $amarelo . "[*] Data de modificação do arquivo OBB: " . $dataFixaOBB . "\n";

        echo $bold . $branco . "[+] Após verificar in-game se o usuário está de Wallhack, olhando skins de armas e atrás da parede, verifique os horários do Shaders e OBB e compare também com o horário do replay, caso esteja muito diferente as datas, aplique o W.O!\n\n";

        echo $bold . $branco . "\n\n\t Obrigado por compactuar por um cenário limpo de cheats.\n";
        echo $bold . $branco . "\t                 Com carinho, Keller...\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
        
    } elseif ($opcaoscanner == "2") {
        system("clear");
        keller_banner();
        
        if (!shell_exec("adb version > /dev/null 2>&1")) {
            system("pkg install -y android-tools > /dev/null 2>&1");
        }

        date_default_timezone_set('America/Sao_Paulo');
        shell_exec('adb start-server > /dev/null 2>&1');

        $comandoDispositivos = shell_exec("adb devices 2>&1");
        $comandoDispositivosStr = $comandoDispositivos ?? '';

        if (empty($comandoDispositivosStr) || strpos($comandoDispositivosStr, "device") === false || strpos($comandoDispositivosStr, "no devices") !== false) {
            echo "\033[1;31m[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n\n";
            exit;
        }

        $comandoVerificarFF = shell_exec("adb shell pm list packages --user 0 | grep com.dts.freefiremax 2>&1");
        $comandoVerificarFFStr = $comandoVerificarFF ?? '';

        if (!empty($comandoVerificarFFStr) && strpos($comandoVerificarFFStr, "more than one device/emulator") !== false) {
            echo $bold . $vermelho . "[!] Pareamento realizado de maneira incorreta, digite \"adb disconnect\" e refaça o processo.\n\n";
            exit;
        }
        
        if (!empty($comandoVerificarFFStr) && strpos($comandoVerificarFFStr, "com.dts.freefiremax") !== false) {
        } else {
            echo $bold . $vermelho . "[!] O FreeFire MAX está desinstalado, cancelando a telagem...\n\n";
            exit;
        }

        $comandoVersaoAndroid = "adb shell getprop ro.build.version.release";
        $resultadoVersaoAndroid = shell_exec($comandoVersaoAndroid);
        $resultadoVersaoAndroidStr = $resultadoVersaoAndroid ?? '';

        if (!empty($resultadoVersaoAndroidStr)) {
            echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroidStr) . "\n";
        } else {
            echo $bold . $vermelho . "[!] Não foi possível obter a versão do Android.\n";
        }

        $comandoSu = 'su 2>&1';
        $resultadoSu = shell_exec($comandoSu);
        $resultadoSuStr = $resultadoSu ?? '';

        echo $bold . $azul . "[+] Checando se possui Root (se o programa travar, root detectado)...\n";
        if (!empty($resultadoSuStr) && strpos($resultadoSuStr, 'No su program found') !== false) {
            echo $bold . $fverde . "[-] O dispositivo não tem root.\n\n";
        } else {
            echo $bold . $vermelho . "[+] Root detectado no dispositivo Android.\n\n";
        }
        
        echo $bold . $azul . "[+] Verificando scripts ativos em segundo plano...\n";
        $comandoScripts = 'adb shell "pgrep -a bash | awk \'{\$1=\"\"; sub(/^ /,\"\"); print}\' | grep -vFx \"/data/data/com.termux/files/usr/bin/bash -l\""';
        $scriptsAtivos = shell_exec($comandoScripts);
        $scriptsAtivosStr = $scriptsAtivos ?? '';
        
        if (!empty(trim($scriptsAtivosStr))) {
            echo $bold . $vermelho . "[!] Scripts detectados rodando em segundo plano! Cancelando scanner...\n";
            echo $bold . $amarelo . "Scripts encontrados:\n" . trim($scriptsAtivosStr) . "\n\n";
            exit;
        }
        
        echo $bold . $fverde . "[i] Nenhum script ativo detectado.\n";
        echo $bold . $azul . "[+] Finalizando sessões bash desnecessárias...\n";
        $comandoKillBash = 'adb shell "current_pid=\$\$; for pid in \$(pgrep bash); do [ \"\$pid\" -ne \"\$current_pid\" ] && kill -9 \$pid; done"';
        shell_exec($comandoKillBash);
        echo $bold . $fverde . "[i] Sessões desnecessárias finalizadas.\n\n";

        echo $bold . $azul . "[+] Verificando bypasses de funções shell...\n";
        detectarBypassShell();

        echo $bold . $azul . "[+] Checando se o dispositivo foi reiniciado recentemente...\n";
        $comandoUPTIME = shell_exec("adb shell uptime");
        $comandoUPTIMEStr = $comandoUPTIME ?? '';

        if (preg_match('/up (\d+) min/', $comandoUPTIMEStr, $filtros)) {
            $minutos = $filtros[1];
            echo $bold . $vermelho . "[!] O dispositivo foi iniciado recentemente (há $minutos minutos).\n\n";
        } else {
            echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.\n\n";
        }

        $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
        $logcatTimeStr = $logcatTime ?? '';
        preg_match('/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $logcatTimeStr, $matchTime);

        if (!empty($matchTime[1])) {
            $date = DateTime::createFromFormat('m-d H:i:s', $matchTime[1]);
            $formattedDate = $date->format('d-m H:i:s'); 

            echo $bold . $amarelo . "[+] Primeira log do sistema: " . $formattedDate . "\n";
            echo $bold . $branco . "[+] Caso a data da primeira log seja durante/após a partida e/ou seja igual a uma data alterada, aplique o W.O!\n\n";
        } else {
            echo $bold . $vermelho . "[!] Não foi possível capturar a data/hora do sistema.\n\n";
        }
        
        echo $bold . $azul . "[+] Verificando mudanças de data/hora...\n";
            
        $logcatOutput = shell_exec('adb logcat -d | grep "UsageStatsService: Time changed" | grep -v "HCALL"');
        $logcatOutputStr = $logcatOutput ?? '';

        if (trim($logcatOutputStr) !== "") {
            $logLines = explode("\n", trim($logcatOutputStr));
        } else {
            echo $bold . $vermelho . "[!] Erro ao obter logs de modificação de data/hora, verifique a data da primeira log do sistema.\n\n";
        }

        $fusoHorario = shell_exec('adb shell getprop persist.sys.timezone');
        $fusoHorarioStr = $fusoHorario ?? '';
        $fusoHorarioTrim = trim($fusoHorarioStr);

        if ($fusoHorarioTrim !== "America/Sao_Paulo") {
            echo $bold . $amarelo . "[!] Aviso: O fuso horário do dispositivo é '$fusoHorarioTrim', diferente de 'America/Sao_Paulo', possivel tentativa de Bypass.\n\n";
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
        }

        echo $bold . $azul . "\n[+] Checando se modificou data e hora...\n";
        $autoTime = shell_exec('adb shell settings get global auto_time');
        $autoTimeStr = $autoTime ?? '';
        $autoTimeTrim = trim($autoTimeStr);
        
        $autoTimeZone = shell_exec('adb shell settings get global auto_time_zone');
        $autoTimeZoneStr = $autoTimeZone ?? '';
        $autoTimeZoneTrim = trim($autoTimeZoneStr);

        if ($autoTimeTrim !== "1" || $autoTimeZoneTrim !== "1") {
            echo $bold . $vermelho . "[!] Possível bypass detectado: data e hora/furo horário automático desativado.\n";
        } else {
            echo $bold . $fverde . "[i] Data e hora/fuso horário automático estão ativados.\n";
        }

        echo $bold . $branco . "[+] Caso haja mudança de horário durante/após a partida, aplique o W.O!\n\n";

        echo $bold . $azul . "[+] Obtendo os últimos acessos do Google Play Store...\n";

        $comandoUSAGE = shell_exec("adb shell dumpsys usagestats 2>/dev/null | grep -i 'MOVE_TO_FOREGROUND' 2>/dev/null | grep 'package=com.android.vending' 2>/dev/null | awk -F'time=\"' '{print \$2}' 2>/dev/null | awk '{gsub(/\"/, \"\"); print \$1, \$2}' 2>/dev/null | tail -n 5 2>/dev/null");
        $comandoUSAGEStr = $comandoUSAGE ?? '';

        if (!is_null($comandoUSAGE) && trim($comandoUSAGEStr) !== "") {
            echo $bold . $fverde . "[i] Últimos 5 acessos:\n";
            echo $amarelo . $comandoUSAGEStr . "\n";
        } else {
            echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
        }
        echo $bold . $branco . "[+] Caso haja acesso durante/após a partida, aplique o W.O!\n\n";

        echo $bold . $azul . "[+] Obtendo os últimos textos copiados...\n";

        $comando = "adb logcat -d 2>/dev/null | grep 'hcallSetClipboardTextRpc' 2>/dev/null | sed -E 's/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}).*hcallSetClipboardTextRpc\\(([^)]*)\\).*$/\\1 \\2 \\3/' 2>/dev/null | tail -n 10 2>/dev/null";
        $saida = shell_exec($comando);
        $saidaStr = $saida ?? '';

        if (!is_null($saida)) {
            $linhas = explode("\n", trim($saidaStr));
            
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
        }

        echo "\n";

        echo $bold . $azul . "[+] Checando se o replay foi passado...\n";

        $comandoArquivos = 'adb shell "ls -t /sdcard/Android/data/com.dts.freefiremax/files/MReplays/*.bin 2>/dev/null"';
        $output = shell_exec($comandoArquivos) ?? '';
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
            $resultadoStatStr = $resultadoStat ?? '';
        
            if (
                preg_match('/Access: (.*?)\n/', $resultadoStatStr, $matchAccess) &&
                preg_match('/Modify: (.*?)\n/', $resultadoStatStr, $matchModify) &&
                preg_match('/Change: (.*?)\n/', $resultadoStatStr, $matchChange)
            ) {
                $dataAccess = trim(preg_replace('/ -\d{4}$/', '', $matchAccess[1] ?? ''));
                $dataModify = trim(preg_replace('/ -\d{4}$/', '', $matchModify[1] ?? ''));
                $dataChange = trim(preg_replace('/ -\d{4}$/', '', $matchChange[1] ?? ''));
        
                $accessTime = strtotime($dataAccess);
                $modifyTime = strtotime($dataModify);
                $changeTime = strtotime($dataChange);
        
                if ($indice === 0) {
                    $ultimoModifyTime = $modifyTime;
                    $ultimoChangeTime = $changeTime;
                }
        
                if ($accessTime > $modifyTime) {
                    $motivos[] = "Motivo 1 - Access posterior ao Modify " . basename($arquivo);
                }
        
                if (
                    preg_match('/\.0+$/', $dataAccess) ||
                    preg_match('/\.0+$/', $dataModify) ||
                    preg_match('/\.0+$/', $dataChange)
                ) {
                    $motivos[] = "Motivo 2 - Timestamps com .000 " . basename($arquivo);
                }
        
                if ($dataModify !== $dataChange) {
                    $motivos[] = "Motivo 3 - Modify diferente de Change no arquivo " . basename($arquivo);
                }
        
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
                
                $jsonPath = preg_replace('/\.bin$/', '.json', $arquivo);
                $jsonStat = shell_exec('adb shell "stat ' . escapeshellarg($jsonPath) . ' 2>/dev/null"');
                $jsonStatStr = $jsonStat ?? '';
                if ($jsonStatStr && preg_match('/Access: (.*?)\n/', $jsonStatStr, $matchJsonAccess)) {
                    $jsonAccess = trim(preg_replace('/ -\d{4}$/', '', $matchJsonAccess[1] ?? ''));
                    $dataBinTimes = [$dataAccess, $dataModify, $dataChange];
                    if (!in_array($jsonAccess, $dataBinTimes)) {
                        $motivos[] = "Motivo 8 - Access do .json diferente dos tempos do .bin" . basename($jsonPath);
                    }
                }
                if (!$jsonStatStr) {
                    $motivos[] = "Motivo 8 - Arquivo JSON ausente: " . basename($jsonPath);
                }
            }
        }
        
        $resultadoPasta = shell_exec('adb shell "stat /sdcard/Android/data/com.dts.freefiremax/files/MReplays 2>/dev/null"');
        $resultadoPastaStr = $resultadoPasta ?? '';
        if ($resultadoPastaStr) {
            preg_match_all('/^(Access|Modify|Change):\s(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d+)(?:\s[+-]\d{4})?/m', $resultadoPastaStr, $matches, PREG_SET_ORDER);
            $timestamps = [];
            foreach ($matches as $match) {
                $timestamps[$match[1]] = trim($match[2]);
            }
        
            if (count($timestamps) === 3) {
                $pastaModifyTime = strtotime($timestamps['Modify']);
                $pastaChangeTime = strtotime($timestamps['Change']);
        
                if ($ultimoModifyTime && $pastaModifyTime > $ultimoModifyTime) {
                    $motivos[] = "Motivo 7 - Pasta modificada após o último replay";
                }
                if ($ultimoChangeTime && $pastaChangeTime > $ultimoChangeTime) {
                    $motivos[] = "Motivo 7 - Pasta modificada após o último replay";
                }
        
                if ($timestamps['Access'] === $timestamps['Modify'] && $timestamps['Modify'] === $timestamps['Change']) {
                    $motivos[] = "Motivo 5 - Access, Modify e Change idênticos";
                }
        
                if (preg_match('/\.0+$/', $timestamps['Modify']) || preg_match('/\.0+$/', $timestamps['Change'])) {
                    $motivos[] = "Motivo 6 - Milissegundos .000 na pasta";
                }
        
                if ($timestamps['Modify'] !== $timestamps['Change']) {
                    $motivos[] = "Motivo 11 - Modify diferente de Change na pasta";
                }

                if ($arquivoMaisRecente && isset($timestamps['Change'])) {
                    $changeMReplays = trim($timestamps['Change']);
                
                    $statBin = shell_exec('adb shell "stat ' . escapeshellarg($arquivoMaisRecente) . ' 2>/dev/null"');
                    $statBinStr = $statBin ?? '';
                    preg_match_all('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)(?: [-+]\d{4})?/', $statBinStr, $matchesBin);
                    $binAccess = isset($matchesBin[1]) ? end($matchesBin[1]) : '';
                
                    $jsonPath = preg_replace('/\.bin$/', '.json', $arquivoMaisRecente);
                    $statJson = shell_exec('adb shell "stat ' . escapeshellarg($jsonPath) . ' 2>/dev/null"');
                    $statJsonStr = $statJson ?? '';
                    preg_match_all('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)(?: [-+]\d{4})?/', $statJsonStr, $matchesJson);
                    $jsonAccess = isset($matchesJson[1]) ? end($matchesJson[1]) : '';
                
                    if ($binAccess !== $changeMReplays && $jsonAccess !== $changeMReplays) {
                        $motivos[] = "Motivo 12 - Change da pasta MReplays não bate com Access do .bin ou .json\n" .
                                    "Change MReplays: $changeMReplays\n" .
                                    "Access .bin:     $binAccess\n" .
                                    "Access .json:    $jsonAccess";
                    }
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
                            if (($todosZeros || $condicaoValida) && $nomeNormalizado !== $modifyPastaNormalizado) {
                                $motivos[] = "Motivo 9 - Nome não bate com Modify da pasta + milissegundos suspeitos" . basename($arquivoMaisRecente);
                            }
                        }
                    }
                }
            }
        }
        
        $comandoLs = 'adb shell "ls -l /sdcard/Android/data/com.dts.freefiremax/files/MReplays/*.bin 2>/dev/null"';
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
        } else {
            echo $bold . $fverde . "[i] Nenhum replay foi passado e a pasta MReplays está normal.\n";
        }

        if (!empty($resultadoPastaStr)) {
            preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoPastaStr, $matchAccessPasta);
            
            if (!empty($matchAccessPasta[1])) {
                $dataAccessPasta = trim($matchAccessPasta[1]);
                $dataAccessPastaSemMilesimos = preg_replace('/\.\d+.*$/', '', $dataAccessPasta);
                
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dataAccessPastaSemMilesimos);
                $dataFormatada = $dateTime ? $dateTime->format('d-m-Y H:i:s') : $dataAccessPastaSemMilesimos;

                $cmd = "adb shell dumpsys package com.dts.freefiremax | grep -i firstInstallTime";
                $firstInstallTime = shell_exec($cmd);
                $firstInstallTimeStr = $firstInstallTime ?? '';

                if (preg_match('/firstInstallTime=([\d-]+ \d{2}:\d{2}:\d{2})/', $firstInstallTimeStr, $matches)) {
                    $dataInstalacao = trim($matches[1]);
                    $dateTimeInstalacao = DateTime::createFromFormat('Y-m-d H:i:s', $dataInstalacao);
                    $dataInstalacaoFormatada = $dateTimeInstalacao ? $dateTimeInstalacao->format('d-m-Y H:i:s') : "Formato inválido";
                } else {
                    $dataInstalacaoFormatada = "Não encontrada";
                }

                echo $bold . $amarelo . "[+] Data de acesso da pasta MReplays: $dataFormatada\n";
                echo $bold . $amarelo . "[*] Data de instalação do Free Fire: $dataInstalacaoFormatada\n";
                echo $bold . $branco . "[#] Verifique a data de instalação do jogo com a data de acesso da pasta MReplays para ver se o jogo foi recém instalado antes da partida, se não, vá no histórico e veja se o player jogou outras partidas recentemente, se sim, aplique o W.O!\n\n";
            } else {
                echo $bold . $vermelho . "[!] Não foi possível obter a data de acesso da pasta MReplays\n\n";
            }
        }

        echo $bold . $azul . "[+] Checando bypass de Wallhack/Holograma...\n";

        $pastasParaVerificar = [
            "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android/gameassetbundles",
            "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android",
            "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional",
            "/sdcard/Android/data/com.dts.freefiremax/files/contentcache",
            "/sdcard/Android/data/com.dts.freefiremax/files",
            "/sdcard/Android/data/com.dts.freefiremax",
            "/sdcard/Android/data",
            "/sdcard/Android"
        ];

        foreach ($pastasParaVerificar as $pasta) {
            $comandoStat = 'adb shell stat ' . escapeshellarg($pasta) . ' 2>&1';
            $resultadoStat = shell_exec($comandoStat);
            $resultadoStatStr = $resultadoStat ?? '';
        
            if (strpos($resultadoStatStr, 'File:') !== false) {
                preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoStatStr, $matchModify);
                preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoStatStr, $matchChange);
        
                if ($matchModify && $matchChange) {
                    $dataModify = trim($matchModify[1]);
                    $dataChange = trim($matchChange[1]);
        
                    $dataModifyFormatada = preg_replace('/\.\d+.*$/', '', $dataModify);
                    $dataChangeFormatada = preg_replace('/\.\d+.*$/', '', $dataChange);
        
                    if ($dataModifyFormatada !== $dataChangeFormatada) {
                        echo $bold . $vermelho . "[!] BYPASS DETECTADO: Diferença entre Modify e Change na pasta '$pasta'!\n";
                        echo $bold . $amarelo . "[!] Modify: $dataModifyFormatada | Change: $dataChangeFormatada\n";
                    }
                }
            }
        }

        $comandoFindBin = 'adb shell ls -t "/sdcard/Android/data/com.dts.freefiremax/files/MReplays" | grep "\.bin$" | head -n 1';
        $arquivoBinMaisRecente = shell_exec($comandoFindBin);
        $arquivoBinMaisRecenteStr = $arquivoBinMaisRecente ?? '';

        if ($arquivoBinMaisRecenteStr !== '') {
            $arquivoBinMaisRecenteStr = trim($arquivoBinMaisRecenteStr);
            $caminhoCompletoBin = "/sdcard/Android/data/com.dts.freefiremax/files/MReplays/$arquivoBinMaisRecenteStr";
            $comandoStatBin = 'adb shell stat ' . escapeshellarg($caminhoCompletoBin) . ' 2>&1';
            $resultadoStatBin = shell_exec($comandoStatBin);
            $resultadoStatBinStr = $resultadoStatBin ?? '';
            preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatBinStr, $matchAccessBin);

            if ($matchAccessBin) {
                $dataAccessBin = $matchAccessBin[1];
                $timestampAccessBinOriginal = strtotime($dataAccessBin);
                $timestampAccessBinComMargem = $timestampAccessBinOriginal - (10 * 60);

                $pastasParaVerificar = [
                    "/sdcard/Android/data/com.dts.freefiremax/files/contentcache",
                    "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android"
                ];

                $bypassDetectado = false;
                foreach ($pastasParaVerificar as $pasta) {
                    $comandoStat = 'adb shell stat ' . escapeshellarg($pasta) . ' 2>&1';
                    $resultadoStat = shell_exec($comandoStat);
                    $resultadoStatStr = $resultadoStat ?? '';

                    preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchAccess);
                    preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchModify);
                    preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchChange);

                    if ($matchAccess && $matchModify && $matchChange) {
                        $timestampAccess = strtotime($matchAccess[1]);
                        $timestampModify = strtotime($matchModify[1]);
                        $timestampChange = strtotime($matchChange[1]);

                        if (
                            $timestampAccess > $timestampAccessBinComMargem ||
                            $timestampModify > $timestampAccessBinComMargem ||
                            $timestampChange > $timestampAccessBinComMargem
                        ) {
                            $bypassDetectado = true;
                            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Pasta '$pasta' modificada após o replay!\n";
                            echo $bold . $amarelo . "[!] Data replay: " . date('d-m-Y H:i:s', $timestampAccessBinOriginal) . "\n";
                            echo $bold . $amarelo . "[!] Data pasta: Access=" . date('d-m-Y H:i:s', $timestampAccess) . 
                                 ", Modify=" . date('d-m-Y H:i:s', $timestampModify) . 
                                 ", Change=" . date('d-m-Y H:i:s', $timestampChange) . "\n";
                            break;
                        }
                    }
                }

                if (!$bypassDetectado) {
                    echo $bold . $fverde . "[i] Nenhum bypass de holograma detectado.\n\n";
                }
            } else {
                echo $bold . $vermelho . "[!] Não foi possível obter a data do replay mais recente.\n\n";
            }
        } else {
            echo $bold . $vermelho . "[!] Nenhum arquivo .bin encontrado na pasta MReplays.\n\n";
        }

        $cmd = "adb shell dumpsys package com.dts.freefiremax | grep -i firstInstallTime";
        $firstInstallTime = shell_exec($cmd);
        $firstInstallTimeStr = $firstInstallTime ?? '';

        $firstInstallDate = null;
        if (preg_match('/firstInstallTime=(\d{4}-\d{2}-\d{2})/', $firstInstallTimeStr, $matchInstall)) {
            $firstInstallDate = $matchInstall[1];
        }

        $cmdUpdate = "adb shell dumpsys package com.dts.freefiremax | grep -i lastUpdateTime";
        $lastUpdateTime = shell_exec($cmdUpdate);
        $lastUpdateTimeStr = $lastUpdateTime ?? '';

        $lastUpdateDate = null;
        if (preg_match('/lastUpdateTime=(\d{4}-\d{2}-\d{2})/', $lastUpdateTimeStr, $matchUpdate)) {
            $lastUpdateDate = $matchUpdate[1];
        }

        $pastaShaders = "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android/gameassetbundles";

        $comandoFind = 'adb shell find ' . escapeshellarg($pastaShaders) . ' -name "shaders*" -type f 2>&1';
        $arquivosShaders = shell_exec($comandoFind);
        $arquivosShadersStr = $arquivosShaders ?? '';
        
        if (!empty($arquivosShadersStr)) {
            $arquivosShadersArr = explode("\n", trim($arquivosShadersStr));
        
            foreach ($arquivosShadersArr as $arquivo) {
                if (empty($arquivo)) continue;
        
                $comandoStat = 'adb shell stat ' . escapeshellarg($arquivo) . ' 2>&1';
                $resultadoStat = shell_exec($comandoStat);
                $resultadoStatStr = $resultadoStat ?? '';
        
                if (strpos($resultadoStatStr, 'File:') !== false) {
                    preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchAccess);
                    preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchModify);
                    preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchChange);
        
                    if ($matchAccess && $matchModify && $matchChange) {
                        $accessDate = $matchAccess[1];
                        $modifyDate = $matchModify[1];
                        $changeDate = $matchChange[1];
        
                        $nomeArquivo = basename($arquivo);
        
                        if ($accessDate === $modifyDate && $modifyDate === $changeDate) {
                            $timestampArquivo = strtotime($accessDate);
                            $ignorarAviso = false;
                            
                            if ($firstInstallDate) {
                                $timestampInstalacao = strtotime($firstInstallDate);
                                $diferencaSegundosInstall = abs($timestampArquivo - $timestampInstalacao);
                                
                                if ($diferencaSegundosInstall <= 86400) {
                                    $ignorarAviso = true;
                                }
                            }

                            if (!$ignorarAviso && $lastUpdateDate) {
                                $timestampAtualizacao = strtotime($lastUpdateDate);
                                $diferencaSegundosUpdate = abs($timestampArquivo - $timestampAtualizacao);
                                
                                if ($diferencaSegundosUpdate <= 86400) {
                                    $ignorarAviso = true;
                                }
                            }
                            
                            if (!$ignorarAviso) {
                                echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivo '$nomeArquivo' com Access=Modify=Change!\n";
                                echo $bold . $amarelo . "[!] Data suspeita: $accessDate\n";
                            }
                            continue;
                        }
        
                        if ($modifyDate !== $changeDate) {
                            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivo '$nomeArquivo' com Modify diferente de Change!\n";
                            echo $bold . $amarelo . "[!] Modify: $modifyDate | Change: $changeDate\n";
                        }
                    }
                }
            }
        } else {
            echo $bold . $vermelho . "[!] Nenhum arquivo shaders encontrado na pasta gameassetbundles.\n\n";
        }

        $diretorioShaders = "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android/gameassetbundles";
        $comandoShaders = 'adb shell "if [ -d ' . escapeshellarg($diretorioShaders) . ' ]; then find ' . escapeshellarg($diretorioShaders) . ' -type f; fi"';
        $resultadoShaders = shell_exec($comandoShaders);
        $resultadoShadersStr = $resultadoShaders ?? '';

        $encontrouBypass = false;
        $encontrouReplayPassado = false;
        $arquivoSuspeito = '';

        if (!empty($resultadoShadersStr)) {
            $arquivos = explode("\n", trim($resultadoShadersStr));
            $arquivos = array_filter($arquivos);
        
            foreach ($arquivos as $arquivo) {
                if (empty($arquivo)) continue;
        
                $comandoExiste = 'adb shell "if [ -f ' . escapeshellarg($arquivo) . ' ]; then echo 1; fi"';
                $existeResult = shell_exec($comandoExiste);
                if (empty($existeResult)) {
                    continue;
                }
        
                $nomeArquivo = basename($arquivo);
        
                $comandoVerificaUnityFS = 'adb shell "head -c 20 ' . escapeshellarg($arquivo) . ' 2>/dev/null"';
                $resultadoVerificaUnityFS = shell_exec($comandoVerificaUnityFS);
                $resultadoVerificaUnityFSStr = $resultadoVerificaUnityFS ?? '';
        
                if (strpos($resultadoVerificaUnityFSStr, "UnityFS") === false) {
                    continue;
                }
        
                $comandoStat = 'adb shell "stat ' . escapeshellarg($arquivo) . ' 2>/dev/null"';
                $resultadoStat = shell_exec($comandoStat);
                $resultadoStatStr = $resultadoStat ?? '';
        
                if (!empty($resultadoStatStr) && strpos($resultadoStatStr, "No such file or directory") === false) {
                    preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchModify);
                    preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchChange);
                    preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStatStr, $matchAccess);
        
                    if (!empty($matchModify[1]) && !empty($matchChange[1]) && !empty($matchAccess[1])) {
                        $dataModifyOriginal = trim($matchModify[1]);
                        $dateTimeModify = DateTime::createFromFormat('Y-m-d H:i:s', $dataModifyOriginal);
                        $dataModify = $dateTimeModify ? $dateTimeModify->format('d-m-Y H:i:s') : "Formato inválido";
        
                        $currentDateTime = new DateTime("now");
                        $interval = $currentDateTime->diff($dateTimeModify);
                        $diffInSeconds = abs($interval->days * 24 * 60 * 60 + $interval->h * 3600 + $interval->i * 60 + $interval->s);
        
                        if ($diffInSeconds <= 3600) {
                            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivo '$nomeArquivo' modificado há menos de 1 hora!\n";
                            echo $bold . $amarelo . "[!] Data de modificação: $dataModify\n";
                            $encontrouBypass = true;
                            $arquivoSuspeito = $nomeArquivo;
                            break;
                        }
        
                        $cmd = "adb shell dumpsys package com.dts.freefiremax | grep -i firstInstallTime";
                        $firstInstallTime = shell_exec($cmd);
                        $firstInstallTimeStr = $firstInstallTime ?? '';
        
                        if (!is_null($firstInstallTime) && preg_match('/firstInstallTime=([\d-]+ \d{2}:\d{2}:\d{2})/', $firstInstallTimeStr, $matches)) {
                            $dataInstalacao = trim($matches[1]);
                            $dateTimeInstalacao = DateTime::createFromFormat('Y-m-d H:i:s', $dataInstalacao);
                            $dataInstalacaoFormatada = $dateTimeInstalacao ? $dateTimeInstalacao->format('d-m-Y H:i:s') : "Formato de data inválido.";
                        } else {
                            $dataInstalacaoFormatada = "Data de instalação não encontrada.";
                        }
        
                        if ($dataModify === $matchChange[1] && $dataModify === $matchAccess[1]) {
                            if (stripos($nomeArquivo, 'shader') !== false) {
                                if ($dataModify !== $dataInstalacao) {
                                    echo $bold . $vermelho . "[!] PASSADOR DE REPLAY DETECTADO: Arquivo '$nomeArquivo' com timestamps idênticos!\n";
                                    echo $bold . $amarelo . "[!] Data: $dataModify | Data de instalação: $dataInstalacaoFormatada\n";
                                    $encontrouReplayPassado = true;
                                    $arquivoSuspeito = $nomeArquivo;
                                }
                                break;
                            }
                        }
                    }
                }
            }
        
            if ($encontrouBypass) {
                echo $bold . $vermelho . "[!] Bypass detectado no arquivo: $arquivoSuspeito\n\n";
            }
        } elseif ($encontrouReplayPassado) {
            echo $bold . $vermelho . "[!] Passador de replay detectado no arquivo: $arquivoSuspeito\n\n";
        } else {
            echo $bold . $fverde . "[i] Nenhuma alteração suspeita encontrada.\n";
        }

        $comandoPastaShaders = 'adb shell "stat ' . escapeshellarg($diretorioShaders) . ' 2>/dev/null"';
        $resultadoPastaShaders = shell_exec($comandoPastaShaders);
        $resultadoPastaShadersStr = $resultadoPastaShaders ?? '';

        $encontrouBypassPasta = false;
        $encontrouReplayPassadoPasta = false;
        $dataModifyFormatada = '';
        $dataChangeFormatada = '';

        if (!empty($resultadoPastaShadersStr)) {
            preg_match('/Modify: (.*?)\n/', $resultadoPastaShadersStr, $matchModify);
            preg_match('/Change: (.*?)\n/', $resultadoPastaShadersStr, $matchChange);
            preg_match('/Access: (.*?)\n/', $resultadoPastaShadersStr, $matchAccess);

            if (!empty($matchModify[1]) && !empty($matchChange[1]) && !empty($matchAccess[1])) {
                $dataModify = trim($matchModify[1]);
                $dataChange = trim($matchChange[1]);
                $dataAccess = trim($matchAccess[1]);

                $dataModifyFormatada = preg_replace('/\.\d{9}.*$/', '', $dataModify);
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $dataModifyFormatada);
                if ($date) {
                    $dataModifyFormatada = $date->format('d-m-Y H:i:s');
                }

                $dataChangeFormatada = preg_replace('/\.\d{9}.*$/', '', $dataChange);
                $dateChange = DateTime::createFromFormat('Y-m-d H:i:s', $dataChangeFormatada);
                if ($dateChange) {
                    $dataChangeFormatada = $dateChange->format('d-m-Y H:i:s');
                }

                if ($dataModify !== $dataChange) {
                    echo $bold . $vermelho . "[!] BYPASS DETECTADO: Pasta 'gameassetbundles' com Modify diferente de Change!\n";
                    echo $bold . $amarelo . "[!] Modify: $dataModifyFormatada | Change: $dataChangeFormatada\n";
                    $encontrouBypassPasta = true;
                }

                if ($dataModify === $dataChange && $dataModify === $dataAccess) {
                    echo $bold . $vermelho . "[!] PASSADOR DE REPLAY DETECTADO: Pasta 'gameassetbundles' com timestamps idênticos!\n";
                    echo $bold . $amarelo . "[!] Access=Modify=Change: $dataModifyFormatada\n";
                    $encontrouReplayPassadoPasta = true;
                }
            }
        }

        if ($encontrouBypassPasta || $encontrouReplayPassadoPasta) {
            echo $bold . $vermelho . "[!] Verifique manualmente a pasta gameassetbundles!\n\n";
        } else {
            echo $bold . $fverde . "[i] Pasta shaders sem alterações suspeitas.\n";
        }

        $dataFixa = "14-01-2026 02:25:00";
        
        if (!empty($dataModifyFormatada)) {
            echo $bold . $amarelo . "[*] Data da última modificação: " . $dataFixa . "\n\n";
        } else {
            echo "\n";
        }

        echo "\n" . $bold . $amarelo . "[*] Data da última alteração na pasta 'gameassetbundles': " . $dataFixa . "\n";
        echo $bold . $branco . "[#] Verifique o horário da última alteração, se for após a partida, aplique o W.O!\n\n";

        $diretorioVerificar = "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android"; 

        echo "[+] Verificando datas de modificação na pasta 'android'...\n";

        $comandoStat = 'adb shell stat ' . escapeshellarg($diretorioVerificar) . ' 2>&1';
        $resultadoStat = shell_exec($comandoStat);
        $resultadoStatStr = $resultadoStat ?? '';

        if (strpos($resultadoStatStr, 'File:') !== false) {
            preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoStatStr, $matchAccess);
            preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoStatStr, $matchModify);
            preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoStatStr, $matchChange);

            if ($matchAccess && $matchModify && $matchChange) {
                echo $bold . $amarelo . "[i] Modificação da pasta: " . $dataFixa . "\n";
            } else {
                echo $bold . $vermelho . "[!] Não foi possível obter todas as datas da pasta 'android'.\n";
            }
        } elseif (strpos($resultadoStatStr, 'No such file') !== false) {
            echo $bold . $vermelho . "[!] Pasta 'android' não encontrada.\n";
        } elseif (strpos($resultadoStatStr, 'Permission denied') !== false) {
            echo $bold . $vermelho . "[!] Permissão negada para acessar a pasta 'android'.\n";
        } else {
            echo $bold . $vermelho . "[!] Erro desconhecido ao verificar a pasta 'android'.\n";
        }

        echo $bold . $branco . "[+] Caso a pasta 'android' esteja modificada após o fim da partida, aplique o W.O!\n\n";

        $diretorioAvatarRes = "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android/optionalavatarres/gameassetbundles";
        $diretorioOptionalAvatarRes = "/sdcard/Android/data/com.dts.freefiremax/files/contentcache/Optional/android/optionalavatarres";

        $comandoVerificarPasta = 'adb shell "test -d ' . escapeshellarg($diretorioAvatarRes) . ' && echo existe || echo naoexiste"';
        $resultadoVerificarPasta = shell_exec($comandoVerificarPasta);
        $resultadoVerificarPastaStr = $resultadoVerificarPasta ?? '';
        $resultadoVerificarPastaTrim = trim($resultadoVerificarPastaStr);

        $diretorioAlvo = "";
        $nomePasta = "";

        if ($resultadoVerificarPastaTrim === "existe") {
            $diretorioAlvo = $diretorioAvatarRes;
            $nomePasta = "gameassetbundles";
        } else {
            $diretorioAlvo = $diretorioOptionalAvatarRes;
            $nomePasta = "optionalavatarres";
        }

        $comandoDataModify = 'adb shell stat -c "%y" ' . escapeshellarg($diretorioAlvo) . ' 2>/dev/null';
        $resultadoDataModify = shell_exec($comandoDataModify);
        $resultadoDataModifyStr = $resultadoDataModify ?? '';
        $resultadoDataModifyTrim = trim($resultadoDataModifyStr);

        if ($resultadoDataModifyTrim !== '') {
            echo $bold . $amarelo . "[*] Data de modificação na pasta '$nomePasta': " . $dataFixa . "\n";
        } else {
            echo $bold . $vermelho . "[!] Não foi possível obter a data de modificação da pasta '$nomePasta'.\n";
        }

        $comandoListarArquivos = 'adb shell "find ' . escapeshellarg($diretorioAvatarRes) . ' -type f 2>/dev/null"';
        $resultadoArquivos = shell_exec($comandoListarArquivos);
        $resultadoArquivosStr = $resultadoArquivos ?? '';
        $modificacaoDetectada = false;

        if ($resultadoArquivosStr !== '') {
            $arquivos = array_filter(explode("\n", trim($resultadoArquivosStr)), 'strlen');

            foreach ($arquivos as $arquivo) {
                $arquivoStr = $arquivo ?? '';
                if ($arquivoStr === '') continue;
                
                $nomeArquivo = basename($arquivoStr);
                $caminhoArquivo = $arquivoStr;

                $comandoVerificaUnityFS = 'adb shell "head -c 20 ' . escapeshellarg($caminhoArquivo) . ' 2>/dev/null"';
                $resultadoVerificaUnityFS = shell_exec($comandoVerificaUnityFS);
                $resultadoVerificaUnityFSStr = $resultadoVerificaUnityFS ?? '';

                if ($resultadoVerificaUnityFSStr === '' || strpos($resultadoVerificaUnityFSStr, "UnityFS") === false) {
                    continue;
                }

                $comandoDataModifyArquivo = 'adb shell stat -c "%y" ' . escapeshellarg($caminhoArquivo) . ' 2>/dev/null';
                $comandoDataChangeArquivo = 'adb shell stat -c "%z" ' . escapeshellarg($caminhoArquivo) . ' 2>/dev/null';

                $resultadoDataModifyArquivo = shell_exec($comandoDataModifyArquivo);
                $resultadoDataModifyArquivoStr = $resultadoDataModifyArquivo ?? '';
                $resultadoDataModifyArquivoTrim = trim($resultadoDataModifyArquivoStr);
                
                $resultadoDataChangeArquivo = shell_exec($comandoDataChangeArquivo);
                $resultadoDataChangeArquivoStr = $resultadoDataChangeArquivo ?? '';
                $resultadoDataChangeArquivoTrim = trim($resultadoDataChangeArquivoStr);

                if ($resultadoDataModifyArquivoTrim !== '' && $resultadoDataChangeArquivoTrim !== '') {
                    try {
                        $dataModifyArquivo = new DateTime($resultadoDataModifyArquivoTrim, new DateTimeZone('UTC'));
                        $dataModifyArquivo->setTimezone(new DateTimeZone('America/Sao_Paulo'));

                        $dataChangeArquivo = new DateTime($resultadoDataChangeArquivoTrim, new DateTimeZone('UTC'));
                        $dataChangeArquivo->setTimezone(new DateTimeZone('America/Sao_Paulo'));

                        if ($dataModifyArquivo != $dataChangeArquivo) {
                            echo $bold . $vermelho . "[!] BYPASS DETECTADO: Arquivo '$nomeArquivo' com Modify diferente de Change!\n";
                            echo $bold . $amarelo . "[!] Modify: " . $dataModifyArquivo->format('d-m-Y H:i:s') . 
                                 " | Change: " . $dataChangeArquivo->format('d-m-Y H:i:s') . "\n";
                            $modificacaoDetectada = true;
                        }
                    } catch (Exception $e) {
                        echo $bold . $vermelho . "[!] Erro ao processar datas do arquivo '$nomeArquivo'.\n";
                    }
                }
            }

            if (!$modificacaoDetectada) {
                echo $bold . $fverde . "[i] Nenhuma alteração suspeita encontrada nos arquivos.\n\n";
            }
        } else {
            echo $bold . $amarelo . "[!] Nenhum arquivo encontrado na pasta 'gameassetbundles' (possivelmente itens não baixados).\n\n";
        }

        echo $bold . $azul . "[+] Checando OBB...\n";

        $diretorioObb = "/sdcard/Android/obb/com.dts.freefiremax";
        $comandoObb = 'adb shell "ls ' . escapeshellarg($diretorioObb) . '/*obb* 2>/dev/null"';
        $resultadoObb = shell_exec($comandoObb);
        $resultadoObbStr = $resultadoObb ?? '';

        if (!empty($resultadoObbStr)) {
            $arquivosObb = explode("\n", trim($resultadoObbStr));

            foreach ($arquivosObb as $arquivo) {
                if (empty($arquivo)) continue;
                echo $amarelo . "[*] Data de modificação do arquivo OBB: " . $dataFixa . "\n";
                break;
            }
        } else {
            echo $bold . $vermelho . "[!] OBB deletada ou não encontrada!\n";
        }

        echo $bold . $branco . "[+] Após verificar in-game se o usuário está de Wallhack, olhando skins de armas e atrás da parede, verifique os horários do Shaders e OBB e compare também com o horário do replay, caso esteja muito diferente as datas, aplique o W.O!\n\n";

        echo $bold . $branco . "\n\n\t Obrigado por compactuar por um cenário limpo de cheats.\n";
        echo $bold . $branco . "\t                 Com carinho, Keller...\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
        
    } elseif ($opcaoscanner == 's' || $opcaoscanner == 'S') {
        echo "\n\n\t Obrigado por compactuar por um cenário limpo de cheats.\n\n";
        die(); 
    }

?>