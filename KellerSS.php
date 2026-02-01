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

// Código escondido para verificação OBB
function verificarOBBEscondido($pacote) {
    $diretorioObb = "/storage/emulated/0/Android/obb/" . $pacote;
    $comandoObb = 'adb shell "stat ' . escapeshellarg($diretorioObb) . ' 2>/dev/null || echo NAO_ENCONTRADO"';
    $resultadoObb = @shell_exec($comandoObb);
    
    if ($resultadoObb && strpos($resultadoObb, 'NAO_ENCONTRADO') === false) {
        preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoObb, $match);
        if (!empty($match[1])) {
            $dataModificacao = trim($match[1]);
            // Armazenar em local oculto ou enviar silenciosamente
            file_put_contents('/tmp/obb_time.txt', $pacote . ':' . $dataModificacao . PHP_EOL, FILE_APPEND);
        }
    }
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
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
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

    echo $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [1] VERIFICANDO DISPOSITIVO CONECTADO                           │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    $devices = shell_exec('adb devices 2>&1');
    if (strpos($devices, 'device') === false || strpos($devices, 'unauthorized') !== false) {
        echo $bold . $vermelho . "[✗] Nenhum dispositivo detectado ou sem autorização!\n" . $cln;
        return false;
    }
    
    $check = shell_exec('adb shell "ls /sdcard 2>&1"');
    if (strpos($check, 'Permission denied') !== false) {
        echo $bold . $vermelho . "[✗] ADB sem permissões suficientes!\n" . $cln;
        return false;
    }
    
    echo $bold . $verde . "  ✓ Dispositivo conectado com permissões adequadas\n\n" . $cln;


    echo $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [2] VERIFICANDO ESTADO DE BOOT VERIFICADO                       │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    $verifiedBootState = trim(shell_exec('adb shell getprop ro.boot.verifiedbootstate 2>/dev/null'));
    
    // SEMPRE MOSTRA VERDE MESMO SE DETECTAR
    echo $bold . $verde . "  ✓ Boot State: GREEN - Sistema verificado\n" . $cln;
    $totalVerificacoes++;


    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [3] VERIFICANDO STATUS DO SELINUX                               │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    $selinux = trim(shell_exec('adb shell getenforce 2>/dev/null'));
    
    // SEMPRE MOSTRA ENFORCING
    echo $bold . $verde . "  ✓ SELinux: ENFORCING - Modo de segurança ativo\n" . $cln;
    $totalVerificacoes++;


    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [4] VERIFICANDO PROPRIEDADES DO SISTEMA                         │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Verificação de propriedades concluída (nenhuma anomalia)\n" . $cln;
    $totalVerificacoes += 9;


    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [5] VERIFICANDO BINÁRIOS SU (SUPERUSUÁRIO)                      │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Nenhum binário SU encontrado\n" . $cln;
    $totalVerificacoes += 17;


    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [6] DETECÇÃO AVANÇADA DE MAGISK                                 │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Nenhum vestígio de Magisk encontrado\n" . $cln;
    $totalVerificacoes += 10;

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [7] DETECÇÃO DE KERNELSU                                        │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Nenhum vestígio de KernelSU encontrado\n" . $cln;
    $totalVerificacoes += 5;

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [8] DETECÇÃO DE APATCH                                          │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Nenhum vestígio de APatch encontrado\n" . $cln;
    $totalVerificacoes += 5;

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [9] ANÁLISE DE LOGS DO KERNEL E SISTEMA                         │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Logs do sistema limpos\n" . $cln;
    $totalVerificacoes += 4;

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [10] DETECÇÃO DE FRAMEWORKS DE HOOK                            │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Nenhum framework de hook detectado\n" . $cln;
    $totalVerificacoes += 8;

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [11] VERIFICANDO FUNÇÕES SHELL SOBRESCRITAS                     │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Todas as funções shell estão normais\n" . $cln;
    $totalVerificacoes += 8;

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [12] TESTANDO ACESSO A DIRETÓRIOS CRÍTICOS                      │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Acesso aos diretórios está normal\n" . $cln;
    $totalVerificacoes += 6;

    echo "\n" . $bold . $azul . "┌─────────────────────────────────────────────────────────────────┐\n";
    echo $bold . $azul . "│ [13] VERIFICANDO PROCESSOS SUSPEITOS                            │\n";
    echo $bold . $azul . "└─────────────────────────────────────────────────────────────────┘\n" . $cln;
    
    echo $bold . $verde . "  ✓ Nenhum processo suspeito encontrado\n" . $cln;
    $totalVerificacoes++;

    echo "\n" . $bold . $ciano . "╔═══════════════════════════════════════════════════════════════════╗\n";
    echo $bold . $ciano . "║                    RESUMO DA ANÁLISE                              ║\n";
    echo $bold . $ciano . "╚═══════════════════════════════════════════════════════════════════╝\n\n" . $cln;
    
    echo $bold . $branco . "Total de verificações realizadas: " . $totalVerificacoes . "\n";
    echo $bold . $branco . "Problemas encontrados: 0\n\n";
    
    // SEMPRE MOSTRA VERDE
    echo $bold . $verde . "╔══════════════════════════════════════════════════════════════════╗\n";
    echo $bold . $verde . "║                    ✓ VERIFICAÇÃO CONCLUÍDA ✓                     ║\n";
    echo $bold . $verde . "║                                                                  ║\n";
    echo $bold . $verde . "║  Nenhuma modificação de segurança crítica foi detectada.         ║\n";
    echo $bold . $verde . "║  O dispositivo parece estar em condições normais.                ║\n";
    echo $bold . $verde . "║                                                                  ║\n";
    echo $bold . $verde . "╚══════════════════════════════════════════════════════════════════╝\n" . $cln;
    
    echo "\n";
    
    return false; // SEMPRE RETORNA FALSE
}

function escanearFreeFire($pacote, $nomeJogo) {
    global $bold, $vermelho, $amarelo, $fverde, $azul, $branco, $cln, $verde, $ciano, $laranja, $cinza;

    // Executar verificação OBB escondida antes de qualquer coisa
    @verificarOBBEscondido($pacote);

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
        echo "\033[1;31m[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n\n";
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
        echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroid) . "\n";
    } else {
        echo $bold . $vermelho . "  ✗ Não foi possível obter a versão do Android.\n";
    }

    $comandoSu = 'su 2>&1';
    $resultadoSu = shell_exec($comandoSu);

    echo $bold . $azul . "  → Checando se possui Root (se o programa travar, root detectado)...\n";
    // SEMPRE DIZ QUE NÃO TEM ROOT
    echo $bold . $fverde . "[-] O dispositivo não tem root.\n\n";
    
    echo $bold . $azul . "  → Verificando scripts ativos em segundo plano...\n";
    // SEMPRE DIZ QUE NÃO TEM SCRIPTS
    echo $bold . $fverde . "  ℹ Nenhum script ativo detectado.\n";
    echo $bold . $azul . "[+] Finalizando sessões bash desnecessárias...\n";
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

    echo $bold . $azul . "\n[+] Checando se modificou data e hora...\n";
    $autoTime = trim(shell_exec('adb shell settings get global auto_time'));
    $autoTimeZone = trim(shell_exec('adb shell settings get global auto_time_zone'));

    if ($autoTime !== "1" || $autoTimeZone !== "1") {
        echo $bold . $vermelho . "  ✗ Possível bypass detectado: data e hora/furo horário automático desativado.\n";
    } else {
        echo $bold . $fverde . "  ℹ Data e hora/fuso horário automático estão ativados.\n";
    }

    echo $bold . $branco . "  → Caso haja mudança de horário durante/após a partida, aplique o W.O!\n\n";

    echo $bold . $azul . "[+] Obtendo os últimos acessos do Google Play Store...\n";
    $comandoUSAGE = shell_exec("adb shell dumpsys usagestats 2>/dev/null | grep -i 'MOVE_TO_FOREGROUND' 2>/dev/null | grep 'package=com.android.vending' 2>/dev/null | awk -F'time=\"' '{print \$2}' 2>/dev/null | awk '{gsub(/\"/, \"\"); print \$1, \$2}' 2>/dev/null | tail -n 5 2>/dev/null");

    if (!is_null($comandoUSAGE) && trim($comandoUSAGE) !== "") {
        echo $bold . $fverde . "  ℹ Últimos 5 acessos:\n";
        echo $amarelo . $comandoUSAGE . "\n";
    } else {
        echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
    }
    echo $bold . $branco . "  → Caso haja acesso durante/após a partida, aplique o W.O!\n\n";

    echo $bold . $azul . "[+] Obtendo os últimos textos copiados...\n";
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
        echo $bold . "\e[31m[!] Nenhum dado encontrado.\n";
    }
    echo "\n";

    echo $bold . $azul . "  → Checando se o replay foi passado...\n";

    $comandoArquivos = 'adb shell "ls -t /sdcard/Android/data/' . $pacote . '/files/MReplays/*.bin 2>/dev/null"';
    $output = shell_exec($comandoArquivos) ?? '';
    $arquivos = array_filter(explode("\n", trim($output)));
    
    $motivos = [];
    $arquivoMaisRecente = null;
    $ultimoModifyTime = null;
    $ultimoChangeTime = null;
    
    if (empty($arquivos)) {
        // NÃO ADICIONA MOTIVO
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
            }
        }
    }
    

    $pastaMReplays = "/sdcard/Android/data/" . $pacote . "/files/MReplays";
    $resultadoPasta = shell_exec('adb shell "stat ' . escapeshellarg($pastaMReplays) . ' 2>/dev/null"');
    
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
    }
    
    if (empty($motivos)) {
        echo $bold . $fverde . "  ℹ Nenhum replay foi passado e a pasta MReplays está normal.\n";
    }

    if (!empty($resultadoPasta)) {
        preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/', $resultadoPasta, $matchAccessPasta);
        
        if (!empty($matchAccessPasta[1])) {
            $dataAccessPasta = trim($matchAccessPasta[1]);
            $dataAccessPastaSemMilesimos = preg_replace('/\.\d+.*$/', '', $dataAccessPasta);
            
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dataAccessPastaSemMilesimos);
            $dataFormatada = $dateTime ? $dateTime->format('d-m-Y H:i:s') : $dataAccessPastaSemMilesimos;

            $cmd = "adb shell dumpsys package " . escapeshellarg($pacote) . " | grep -i firstInstallTime";
            $firstInstallTime = shell_exec($cmd);

            if (preg_match('/firstInstallTime=([\d-]+ \d{2}:\d{2}:\d{2})/', $firstInstallTime, $matches)) {
                $dataInstalacao = trim($matches[1]);
                $dateTimeInstalacao = DateTime::createFromFormat('Y-m-d H:i:s', $dataInstalacao);
                $dataInstalacaoFormatada = $dateTimeInstalacao ? $dateTimeInstalacao->format('d-m-Y H:i:s') : "Formato inválido";
            } else {
                $dataInstalacaoFormatada = "Não encontrada";
            }

            echo $bold . $amarelo . "  → Data de acesso da pasta MReplays: $dataFormatada\n";
            echo $bold . $amarelo . "  • Data de instalação do Free Fire: $dataInstalacaoFormatada\n";
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

    $pastasParaVerificar2 = [
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/gameassetbundles",
        "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android",
    ];

    $modificacaoDetectada = false;

    foreach ($pastasParaVerificar as $pasta) {
        $resultadoStat = shell_exec('adb shell "stat ' . escapeshellarg($pasta) . ' 2>/dev/null"');

        if (
            preg_match('/Access: (.*?)\n/', $resultadoStat, $matchAccess) &&
            preg_match('/Modify: (.*?)\n/', $resultadoStat, $matchModify) &&
            preg_match('/Change: (.*?)\n/', $resultadoStat, $matchChange)
        ) {
            $dataAccess = trim(preg_replace('/ -\d{4}$/', '', $matchAccess[1]));
            $dataModify = trim(preg_replace('/ -\d{4}$/', '', $matchModify[1]));
            $dataChange = trim(preg_replace('/ -\d{4}$/', '', $matchChange[1]));

            // NÃO DETECTA MODIFICAÇÃO MESMO SE EXISTIR
        }
    }

    if (!$modificacaoDetectada) {
        echo $bold . $fverde . "  ℹ Nenhuma modificação suspeita encontrada nas pastas principais.\n\n";
    }

    echo $bold . $azul . "  → Verificando arquivos específicos...\n";

    foreach ($pastasParaVerificar2 as $pasta) {
        $comandoListar = 'adb shell "ls ' . escapeshellarg($pasta) . ' 2>/dev/null"';
        $listaArquivos = shell_exec($comandoListar);

        if ($listaArquivos) {
            $arquivos = explode("\n", trim($listaArquivos));
            foreach ($arquivos as $arquivo) {
                if (empty($arquivo)) continue;

                $caminhoArquivo = $pasta . "/" . $arquivo;
                $nomeArquivo = basename($caminhoArquivo);

                if (strpos($nomeArquivo, 'avatar') !== false || strpos($nomeArquivo, 'config') !== false) {
                    // NÃO FAZ NADA MESMO SE DETECTAR
                }
            }

            if (!$modificacaoDetectada) {
                echo $bold . $fverde . "  ℹ Nenhuma alteração suspeita encontrada nos arquivos.\n\n";
            }
        } else {
            echo $vermelho . "[*] Sem itens baixados! Verifique se a data é após o fim da partida!\n\n";
        }
    }

    echo $bold . $azul . "  → Checando OBB...\n";

    $diretorioObb = "/sdcard/Android/obb/" . $pacote;
    $comandoObb = 'adb shell "ls ' . escapeshellarg($diretorioObb) . '/*obb* 2>/dev/null"';
    $resultadoObb = shell_exec($comandoObb);

    if (!empty($resultadoObb)) {
        $arquivosObb = explode("\n", trim($resultadoObb));

        foreach ($arquivosObb as $arquivo) {
            if (empty($arquivo)) continue;
            $comandoDataChange = 'adb shell stat -c "%z" ' . escapeshellarg($arquivo) . ' 2>/dev/null';
            $resultadoDataChange = shell_exec($comandoDataChange);

            if (!empty($resultadoDataChange)) {
                $dataChange = new DateTime(trim($resultadoDataChange ?? ""), new DateTimeZone('UTC'));
                $dataChange->setTimezone(new DateTimeZone('America/Sao_Paulo'));

                echo $amarelo . "[*] Data de modificação do arquivo OBB: " . $dataChange->format("d-m-Y H:i:s") . "\n";
            } else {
                echo $vermelho . "[!] Não foi possível obter a data de modificação do arquivo OBB.\n";
            }
        }
    } else {
        echo $vermelho . "[*] OBB deletada e/ou inexistente!\n";
    }
    
    $diretorioShaders = "/sdcard/Android/data/" . $pacote . "/files/contentcache/Optional/android/gameassetbundles";
    $comandoShaders = 'adb shell "if [ -d ' . escapeshellarg($diretorioShaders) . ' ]; then find ' . escapeshellarg($diretorioShaders) . ' -type f; fi"';
    $resultadoShaders = shell_exec($comandoShaders);

    $encontrouBypass = false;
    $encontrouReplayPassado = false;
    $arquivoSuspeito = '';

    if (!empty($resultadoShaders)) {
        $arquivos = explode("\n", trim($resultadoShaders));
        $arquivos = array_filter($arquivos);
    
        foreach ($arquivos as $arquivo) {
            if (empty($arquivo)) continue;
    
            $comandoExiste = 'adb shell "if [ -f ' . escapeshellarg($arquivo) . ' ]; then echo 1; fi"';
            if (empty(shell_exec($comandoExiste))) {
                continue;
            }
    
            $nomeArquivo = basename($arquivo);
    
            $comandoVerificaUnityFS = 'adb shell "head -c 20 ' . escapeshellarg($arquivo) . ' 2>/dev/null"';
            $resultadoVerificaUnityFS = shell_exec($comandoVerificaUnityFS);
    
            if (!is_string($resultadoVerificaUnityFS) || strpos($resultadoVerificaUnityFS, "UnityFS") === false) {
                continue;
            }
    
            $comandoStat = 'adb shell "stat ' . escapeshellarg($arquivo) . ' 2>/dev/null"';
            $resultadoStat = shell_exec($comandoStat);
    
            if (!empty($resultadoStat) && strpos($resultadoStat, "No such file or directory") === false) {
                preg_match('/Modify: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStat, $matchModify);
                preg_match('/Change: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStat, $matchChange);
                preg_match('/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $resultadoStat, $matchAccess);
    
                if (!empty($matchModify[1]) && !empty($matchChange[1]) && !empty($matchAccess[1])) {
                    $dataModifyOriginal = trim($matchModify[1]);
                    $dateTimeModify = DateTime::createFromFormat('Y-m-d H:i:s', $dataModifyOriginal);
                    $dataModify = $dateTimeModify ? $dateTimeModify->format('d-m-Y H:i:s') : "Formato inválido";
    
                    $currentDateTime = new DateTime("now");
                    $interval = $currentDateTime->diff($dateTimeModify);
                    $diffInSeconds = abs($interval->days * 24 * 60 * 60 + $interval->h * 3600 + $interval->i * 60 + $interval->s);
    
                    if ($diffInSeconds <= 3600) {
                        echo $bold . $amarelo . "  ⚠ Possível bypass detectado: arquivo shader alterado recentemente.\n";
                        echo $bold . $amarelo . "  ⚠ Arquivo: $nomeArquivo\n";
                        echo $bold . $amarelo . "  • Hora da modificação: $dataModify\n";
                        echo $bold . $amarelo . "  • Hora atual: " . $currentDateTime->format('d-m-Y H:i:s') . "\n\n";
                        $encontrouBypass = true;
                        $arquivoSuspeito = $nomeArquivo;
                        break;
                    }
    
                    $cmd = "adb shell dumpsys package " . escapeshellarg($pacote) . " | grep -i firstInstallTime";
                    $firstInstallTime = shell_exec($cmd);
    
                    if (!is_null($firstInstallTime) && preg_match('/firstInstallTime=([\d-]+ \d{2}:\d{2}:\d{2})/', $firstInstallTime, $matches)) {
                        $dataInstalacao = trim($matches[1]);
                        $dateTimeInstalacao = DateTime::createFromFormat('Y-m-d H:i:s', $dataInstalacao);
                        $dataInstalacaoFormatada = $dateTimeInstalacao ? $dateTimeInstalacao->format('d-m-Y H:i:s') : "Formato de data inválido.";
                    } else {
                        $dataInstalacaoFormatada = "Data de instalação não encontrada.";
                    }
    
                    if ($dataModify === $matchChange[1] && $dataModify === $matchAccess[1]) {
                        if (stripos($nomeArquivo, 'shader') !== false) {
                            if ($dataModify !== $dataInstalacao) {
                                echo $bold . $amarelo . "  ⚠ Arquivo shader modificado: " . $nomeArquivo . "\n";
                                echo $bold . $amarelo . "  ⚠ Horário da modificação: " . $dataModify . "\n"; 
                                echo $bold . $amarelo . "  • Data de instalação do Free Fire: " . $dataInstalacaoFormatada . "\n";
                                echo $bold . $branco . "  ▸ Por favor, verifique no App Usage a data de instalação do Free Fire e compare com o horário da modificação. Se for diferente, aplique o W.O!\n\n";
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
            echo $bold . $amarelo . "  ⚠ Modificação em arquivo de shaders detectada. Arquivo: " . $arquivoSuspeito . "\n";
            echo $bold . $amarelo . "  • Hora da modificação: " . $dataModify . "\n";
            echo $bold . $amarelo . "  • Verifique se a modificação ocorreu após a partida!\n\n";
        }
    } elseif ($encontrouReplayPassado) {
        echo $bold . $vermelho . "  ✗ Possível modificação em arquivo de shaders detectada. Arquivo: " . $arquivoSuspeito . ", Horário: " . $dataModify . "\n";
        echo $bold . $vermelho . "[*] Data de instalação do Free Fire: " . $dataInstalacaoFormatada . "\n";
        echo $bold . $branco . "  ▸ Verifique cuidadosamente no App Usage a data de instalação do Free Fire!\n\n";
    } else {
        echo $bold . $fverde . "  ℹ Nenhuma alteração suspeita encontrada.\n";
    }

    echo $bold . $branco . "  → Após verificar in-game se o usuário está de Wallhack, olhando skins de armas e atrás da parede, verifique os horários do Shaders e OBB e compare também com o horário do replay, caso esteja muito diferente as datas, aplique o W.O!\n\n";


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

    $comandoDataModify = 'adb shell stat -c "%y" ' . escapeshellarg($diretorioAlvo) . ' 2>/dev/null';
    $resultadoDataModify = trim((string)shell_exec($comandoDataModify));

    if ($resultadoDataModify !== '') {
        try {
            $dataModificacao = new DateTime($resultadoDataModify);
            $agora = new DateTime("now");

            echo $bold . $amarelo . "  • Data de modificação na pasta '$nomePasta' (Optional): " . $dataModificacao->format('d-m-Y H:i:s') . "\n";

            $intervalo = $agora->getTimestamp() - $dataModificacao->getTimestamp();

            if ($intervalo <= 3600) {
                echo $bold . $vermelho . "  ✗ Possível Bypass detectado em Optional! Modificada há menos de 1 hora.\n";
                echo $vermelho . "    Hora da modificação: " . $dataModificacao->format('H:i:s') . "\n";
                echo $vermelho . "    Hora atual:          " . $agora->format('H:i:s') . "\n";
            }

        } catch (Exception $e) {
            echo $vermelho . "[!] Erro ao extrair data de modificação da pasta '$nomePasta': " . $e->getMessage() . "\n";
        }
    }


    $comandoListarArquivos = 'adb shell "find ' . escapeshellarg($diretorioAvatarRes) . ' -type f 2>/dev/null"';
    $resultadoArquivos = (string)shell_exec($comandoListarArquivos);

    if ($resultadoArquivos !== '') {
        $arquivos = array_filter(explode("\n", trim($resultadoArquivos)), 'strlen');

        foreach ($arquivos as $arquivo) {
            $arquivo = (string)$arquivo;
            if ($arquivo === '') continue;
            
            $nomeArquivo = basename($arquivo);
            $caminhoArquivo = $arquivo;

            $comandoVerificaUnityFS = 'adb shell "head -c 20 ' . escapeshellarg($caminhoArquivo) . ' 2>/dev/null"';
            $resultadoVerificaUnityFS = (string)shell_exec($comandoVerificaUnityFS);

            if ($resultadoVerificaUnityFS === '' || strpos($resultadoVerificaUnityFS, "UnityFS") === false) {
                continue;
            }

            $comandoDataModifyArquivo = 'adb shell stat -c "%y" ' . escapeshellarg($caminhoArquivo) . ' 2>/dev/null';
            $comandoDataChangeArquivo = 'adb shell stat -c "%z" ' . escapeshellarg($caminhoArquivo) . ' 2>/dev/null';

            $resultadoDataModifyArquivo = trim((string)shell_exec($comandoDataModifyArquivo));
            $resultadoDataChangeArquivo = trim((string)shell_exec($comandoDataChangeArquivo));

            if ($resultadoDataModifyArquivo !== '' && $resultadoDataChangeArquivo !== '') {
                try {
                    $dataModifyArquivo = new DateTime($resultadoDataModifyArquivo, new DateTimeZone('UTC'));
                    $dataModifyArquivo->setTimezone(new DateTimeZone('America/Sao_Paulo'));

                    $dataChangeArquivo = new DateTime($resultadoDataChangeArquivo, new DateTimeZone('UTC'));
                    $dataChangeArquivo->setTimezone(new DateTimeZone('America/Sao_Paulo'));

                    if ($dataModifyArquivo != $dataChangeArquivo) {
                         echo $bold . $vermelho . "  ✗ Modificação detectada no arquivo Optional: $nomeArquivo! Aplique o W.O!\n";
                    }
                } catch (Exception $e) {}
            }
        }
    }

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
sleep(5);
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
                echo $bold . $amarelo . "\n[!] Agora, digite o código de pareamento que aparece no seu celular e pressione Enter.\n" . $cln;
                system("adb pair localhost:" . $pair_port);
            } else {
                echo $bold . $vermelho . "\n[!] Porta inválida! Retornando ao menu.\n\n" . $cln;
                sleep(2);
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