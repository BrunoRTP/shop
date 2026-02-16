// Configuración base para todos los gráficos
const layoutConfig = {
  font: {
    family: "Arial, sans-serif",
    size: 12,
  },
  paper_bgcolor: "rgba(0,0,0,0)",
  plot_bgcolor: "rgba(0,0,0,0)",
  margin: { t: 50, r: 80, b: 100, l: 80 },
  height: 450,
  autosize: true,
};

const config = {
  responsive: true,
  displayModeBar: true,
  displaylogo: false,
  modeBarButtonsToRemove: ["lasso2d", "select2d"],
};

// Función para cargar datos
async function loadStats() {
  const baseUrl = "/student025/shop/backend/api/get_stats_data.php";

  try {
    console.log("Cargando estadísticas...");

    // Cargar datos mensuales
    const monthlyResponse = await fetch(baseUrl + "?type=monthly");
    console.log("Monthly response status:", monthlyResponse.status);

    if (!monthlyResponse.ok) {
      throw new Error(
        "Error al cargar datos mensuales: " + monthlyResponse.status,
      );
    }

    const monthlyData = await monthlyResponse.json();
    console.log("Monthly data:", monthlyData);
    renderMonthlyChart(monthlyData);

    // Cargar datos por producto
    const productResponse = await fetch(baseUrl + "?type=product");
    console.log("Product response status:", productResponse.status);

    if (!productResponse.ok) {
      throw new Error(
        "Error al cargar datos de productos: " + productResponse.status,
      );
    }

    const productData = await productResponse.json();
    console.log("Product data:", productData);
    renderProductChart(productData);

    // Cargar tendencia
    const trendResponse = await fetch(baseUrl + "?type=trend");
    console.log("Trend response status:", trendResponse.status);

    if (!trendResponse.ok) {
      throw new Error("Error al cargar tendencia: " + trendResponse.status);
    }

    const trendData = await trendResponse.json();
    console.log("Trend data:", trendData);
    renderTrendChart(trendData);
  } catch (error) {
    console.error("Error loading stats:", error);
    showError();
  }
}

// Gráfico 1: Barras - Ventas Mensuales
function renderMonthlyChart(data) {
  const container = document.getElementById("chart-monthly");

  if (!data || data.length === 0) {
    container.innerHTML = '<div class="error">No hay datos disponibles</div>';
    return;
  }

  // Limpiar el contenedor
  container.innerHTML = "";

  const months = data.map((d) => d.month);
  const revenue = data.map((d) => d.revenue);
  const orders = data.map((d) => d.orders);

  const trace1 = {
    x: months,
    y: revenue,
    name: "Ingresos (€)",
    type: "bar",
    marker: {
      color: "#667eea",
      line: {
        color: "#5568d3",
        width: 1.5,
      },
    },
    hovertemplate: "<b>%{x}</b><br>Ingresos: €%{y:.2f}<extra></extra>",
  };

  const trace2 = {
    x: months,
    y: orders,
    name: "Número de Pedidos",
    type: "bar",
    marker: {
      color: "#f093fb",
      line: {
        color: "#e07de8",
        width: 1.5,
      },
    },
    yaxis: "y2",
    hovertemplate: "<b>%{x}</b><br>Pedidos: %{y}<extra></extra>",
  };

  const layout = {
    ...layoutConfig,
    barmode: "group",
    xaxis: {
      title: "Mes",
      tickangle: -45,
    },
    yaxis: {
      title: "Ingresos (€)",
      titlefont: { color: "#667eea" },
      tickfont: { color: "#667eea" },
    },
    yaxis2: {
      title: "Número de Pedidos",
      titlefont: { color: "#f093fb" },
      tickfont: { color: "#f093fb" },
      overlaying: "y",
      side: "right",
    },
    showlegend: true,
    legend: {
      x: 0.5,
      xanchor: "center",
      y: 1.1,
      orientation: "h",
    },
  };

  Plotly.newPlot("chart-monthly", [trace1, trace2], layout, config);
}

// Gráfico 2: Pie - Distribución por Producto
function renderProductChart(data) {
  const container = document.getElementById("chart-product");

  if (!data || data.length === 0) {
    container.innerHTML = '<div class="error">No hay datos disponibles</div>';
    return;
  }

  // Limpiar el contenedor
  container.innerHTML = "";

  const products = data.map((d) => d.product);
  const revenue = data.map((d) => d.revenue);

  const trace = {
    labels: products,
    values: revenue,
    type: "pie",
    hole: 0.4,
    marker: {
      colors: [
        "#667eea",
        "#764ba2",
        "#f093fb",
        "#4facfe",
        "#43e97b",
        "#fa709a",
        "#fee140",
        "#30cfd0",
        "#a8edea",
        "#fed6e3",
      ],
    },
    textposition: "inside",
    textinfo: "label+percent",
    hovertemplate:
      "<b>%{label}</b><br>Ingresos: €%{value:.2f}<br>%{percent}<extra></extra>",
  };

  const layout = {
    ...layoutConfig,
    showlegend: false,
    annotations: [
      {
        font: { size: 20 },
        showarrow: false,
        text: "Ingresos",
        x: 0.5,
        y: 0.5,
      },
    ],
  };

  Plotly.newPlot("chart-product", [trace], layout, config);
}

// Gráfico 3: Líneas - Tendencia de Ventas
function renderTrendChart(data) {
  const container = document.getElementById("chart-trend");

  if (!data || data.length === 0) {
    container.innerHTML = '<div class="error">No hay datos disponibles</div>';
    return;
  }

  // Limpiar el contenedor
  container.innerHTML = "";

  const dates = data.map((d) => d.date);
  const revenue = data.map((d) => d.revenue);
  const orders = data.map((d) => d.orders);

  const trace1 = {
    x: dates,
    y: revenue,
    name: "Ingresos (€)",
    type: "scatter",
    mode: "lines+markers",
    line: {
      color: "#667eea",
      width: 3,
      shape: "spline",
    },
    marker: {
      size: 8,
      color: "#667eea",
      line: {
        color: "white",
        width: 2,
      },
    },
    fill: "tozeroy",
    fillcolor: "rgba(102, 126, 234, 0.2)",
    hovertemplate: "<b>%{x}</b><br>Ingresos: €%{y:.2f}<extra></extra>",
  };

  const trace2 = {
    x: dates,
    y: orders,
    name: "Pedidos",
    type: "scatter",
    mode: "lines+markers",
    line: {
      color: "#43e97b",
      width: 3,
      shape: "spline",
    },
    marker: {
      size: 8,
      color: "#43e97b",
      line: {
        color: "white",
        width: 2,
      },
    },
    yaxis: "y2",
    hovertemplate: "<b>%{x}</b><br>Pedidos: %{y}<extra></extra>",
  };

  const layout = {
    ...layoutConfig,
    xaxis: {
      title: "Fecha",
      tickangle: -45,
      showgrid: true,
      gridcolor: "rgba(0,0,0,0.1)",
    },
    yaxis: {
      title: "Ingresos (€)",
      titlefont: { color: "#667eea" },
      tickfont: { color: "#667eea" },
      showgrid: true,
      gridcolor: "rgba(0,0,0,0.1)",
    },
    yaxis2: {
      title: "Número de Pedidos",
      titlefont: { color: "#43e97b" },
      tickfont: { color: "#43e97b" },
      overlaying: "y",
      side: "right",
    },
    showlegend: true,
    legend: {
      x: 0.5,
      xanchor: "center",
      y: 1.15,
      orientation: "h",
      bgcolor: "rgba(255,255,255,0.8)",
      bordercolor: "#ddd",
      borderwidth: 1,
    },
    hovermode: "x unified",
  };

  Plotly.newPlot("chart-trend", [trace1, trace2], layout, config);
}

function showError() {
  document.getElementById("chart-monthly").innerHTML =
    '<div class="error">Error al cargar los datos. Por favor, recarga la página.</div>';
  document.getElementById("chart-product").innerHTML =
    '<div class="error">Error al cargar los datos. Por favor, recarga la página.</div>';
  document.getElementById("chart-trend").innerHTML =
    '<div class="error">Error al cargar los datos. Por favor, recarga la página.</div>';
}

// Cargar estadísticas al iniciar
loadStats();
