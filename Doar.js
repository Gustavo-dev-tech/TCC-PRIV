document.addEventListener('DOMContentLoaded', function () {
  const calendarContainer = document.getElementById('calendar');
  const monthNameEl = document.getElementById('month-name');
  const prevMonthBtn = document.getElementById('prev-month');
  const nextMonthBtn = document.getElementById('next-month');

  const monthNames = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
  ];

  const weekDays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

  let currentDate = new Date();
  let avisos = {}; // Para armazenar os avisos carregados do backend

  // Função para renderizar o calendário
  function renderCalendar(date) {
    calendarContainer.innerHTML = ''; // Limpa o calendário
    const month = date.getMonth();
    const year = date.getFullYear();

    // Atualiza o nome do mês
    monthNameEl.textContent = `${monthNames[month]} ${year}`;

    // Adiciona cabeçalhos dos dias da semana
    weekDays.forEach(day => {
      const dayElement = document.createElement('div');
      dayElement.className = 'day day-header';
      dayElement.textContent = day;
      calendarContainer.appendChild(dayElement);
    });

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    // Adiciona espaços vazios
    for (let i = 0; i < firstDay; i++) {
      const emptyCell = document.createElement('div');
      emptyCell.className = 'day';
      calendarContainer.appendChild(emptyCell);
    }

    // Adiciona dias do mês
    for (let day = 1; day <= daysInMonth; day++) {
      const dayElement = document.createElement('div');
      dayElement.className = 'day';
      dayElement.textContent = day;

      const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const currentDay = new Date(year, month, day).getDay();

      // Destaca finais de semana
      if (currentDay === 0 || currentDay === 6) {
        dayElement.classList.add('weekend');
      }

      // Exibe avisos
      if (avisos[dateKey]) {
        dayElement.classList.add('holiday');
        avisos[dateKey].forEach(message => {
          const avisoText = document.createElement('p');
          avisoText.style.color = 'red';
          avisoText.textContent = message;
          dayElement.appendChild(avisoText);
        });
      }

      // Marca o dia atual (hoje) com cor roxa
      const today = new Date();
      if (today.getDate() === day && today.getMonth() === month && today.getFullYear() === year) {
        dayElement.classList.add('today');
      }

      calendarContainer.appendChild(dayElement);
    }
  }

  function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    renderCalendar(currentDate);
  }

  function fetchAvisos() {
    fetch('admin_login.php?action=getAvisos')
      .then(response => response.json())
      .then(data => {
        avisos = data; // Salva os avisos carregados
        renderCalendar(currentDate); // Renderiza o calendário inicial
      })
      .catch(error => console.error('Erro ao carregar avisos:', error));
  }

  // Listeners dos botões
  prevMonthBtn.addEventListener('click', () => changeMonth(-1));
  nextMonthBtn.addEventListener('click', () => changeMonth(1));

  // Inicializa
  fetchAvisos();
});
