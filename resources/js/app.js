import './bootstrap';
import Chart from 'chart.js/auto';

// --- helpers ---
function createGauge(el, value, max) {
  const v = Math.max(0, Math.min(value, max));

  return new Chart(el, {
    type: 'doughnut',
    data: {
      datasets: [{
        data: [v, max - v],
        borderWidth: 0,
        cutout: '75%',
      }]
    },
    options: {
      animation: false,
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: { enabled: false },
      }
    }
  });
}

function updateGauge(chart, value, max) {
  if (!chart) return;
  const v = Math.max(0, Math.min(value ?? 0, max));
  chart.data.datasets[0].data = [v, max - v];
  chart.update();
}

// --- init only if page has gauges ---
const gaugesRoot = document.getElementById('gauges');

if (gaugesRoot) {
  const colmenaId = gaugesRoot.dataset.colmenaId;

  const temp0 = parseFloat(gaugesRoot.dataset.temp) || 0;
  const hum0  = parseFloat(gaugesRoot.dataset.hum) || 0;
  const peso0 = parseFloat(gaugesRoot.dataset.peso) || 0;

  const MAX_TEMP = 60;
  const MAX_HUM  = 100;
  const MAX_PESO = 200;

  const gaugeTempEl = document.getElementById(`gauge-temp-${colmenaId}`);
  const gaugeHumEl  = document.getElementById(`gauge-hum-${colmenaId}`);
  const gaugePesoEl = document.getElementById(`gauge-peso-${colmenaId}`);

  const gaugeTemp = gaugeTempEl ? createGauge(gaugeTempEl, temp0, MAX_TEMP) : null;
  const gaugeHum  = gaugeHumEl  ? createGauge(gaugeHumEl,  hum0,  MAX_HUM)  : null;
  const gaugePeso = gaugePesoEl ? createGauge(gaugePesoEl, peso0, MAX_PESO) : null;

  // realtime
  window.Echo.channel('metrics')
    .listen('.metric.updated', (e) => {
      if (String(e.idColmena) !== String(colmenaId)) return;

      // textos
      const tEl = document.getElementById(`val-temp-${colmenaId}`);
      const hEl = document.getElementById(`val-hum-${colmenaId}`);
      const pEl = document.getElementById(`val-peso-${colmenaId}`);

      if (tEl) tEl.innerText = `${e.temperatura ?? '--'} °C`;
      if (hEl) hEl.innerText = `${e.humedad ?? '--'} %`;
      if (pEl) pEl.innerText = `${e.peso ?? '--'} kg`;

      // diales
      updateGauge(gaugeTemp, e.temperatura, MAX_TEMP);
      updateGauge(gaugeHum,  e.humedad,     MAX_HUM);
      updateGauge(gaugePeso, e.peso,        MAX_PESO);
    });
}

