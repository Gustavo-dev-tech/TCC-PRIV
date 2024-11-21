document.addEventListener("DOMContentLoaded", function() {
    // Função para carregar os agendamentos
    function carregarAgendamentos() {
        fetch('agendamento.json')  // Novo nome do arquivo JSON
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar o arquivo JSON');
                }
                return response.json();
            })
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    exibirAgendamentos(data);  // Chama a função para exibir os agendamentos no calendário
                } else {
                    console.error('Não há agendamentos para exibir.');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar os agendamentos:', error);
            });
    }

    // Função para exibir os agendamentos no calendário
    function exibirAgendamentos(agendamentos) {
        const calendario = document.getElementById('calendario-adm');  // O elemento do calendário no HTML
        if (calendario) {
            calendario.innerHTML = '';  // Limpa o conteúdo anterior

            // Para cada agendamento, cria um item no calendário
            agendamentos.forEach(agendamento => {
                const div = document.createElement('div');
                div.classList.add('agendamento');
                div.innerHTML = `
                    <div class="data-agendamento">${agendamento.data}</div>
                    <div class="etec-agendada">${agendamento.etec}</div>
                    <div class="usuario-agendamento">Agendado em: ${agendamento.timestamp}</div>
                `;
                calendario.appendChild(div);  // Adiciona o agendamento ao calendário
            });
        } else {
            console.error('Elemento calendario-adm não encontrado');
        }
    }

    // Chama a função para carregar os agendamentos quando a página for carregada
    carregarAgendamentos();
});
