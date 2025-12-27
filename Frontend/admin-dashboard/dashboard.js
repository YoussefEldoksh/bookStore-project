document.addEventListener("DOMContentLoaded", async (e) => {
  const ctx = document.getElementById("myChart").getContext("2d");
  const dataset = await fetchOrdersData();
  const ordersContainer = document.getElementById("ordersContainer");
  const userTotalCount = document.getElementById("userTotalCount");
  const totalOrdersPrevMonth = document.getElementById("totalOrdersPrevMonth");
  const totalSalesToday = document.getElementById("totalSalesToday");
  const totalOnSalesMonth = document.getElementById("tableDataMonth");
  const totalOnSalesDay = document.getElementById("tableDataDay");
  const dateNeeded = document.getElementById("exampleDateInput");

  dateNeeded.addEventListener("change", async function () {
    console.log(dateNeeded.textContent);
    const dayData = await TotalSalesPrevOnAGivenDay(dateNeeded.value);
    const dayDataArray = dayData.data;
    if(dayDataArray.length === 0){
        totalOnSalesDay.innerHTML = ``;
    }
    for (const order of dayDataArray) {
      totalOnSalesDay.innerHTML += `
                                <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-medium text-gray-600 border border-gray-200">
                                            </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">${order.first_name} ${order.first_name}</div>
                                            <div class="text-xs text-gray-500">${order.email}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-100">${order.status}</span>
                                </td>
                                <td class="py-4 text-sm text-gray-500">${order.order_date}</td>
                                <td class="py-4 text-sm font-medium text-gray-900 text-right">${order.total_amount}</td>
                            </tr>
    
  `;
    }
  });

  const userCount = await fetchUsersData();
  userTotalCount.textContent = userCount.data[0].userCount;

  const totalPrevMonth = await fetchSalesPrevMonth();
  totalOrdersPrevMonth.textContent = totalPrevMonth.data[0].total_sales;

  const totalToday = await fetchSalesToday();
  totalSalesToday.textContent = totalToday.data[0].total_sales;

  const monthData = await fetchSalesPrevMonthDetailed();
  const monthDataArray = monthData.data;
  for (const order of monthDataArray) {
    totalOnSalesMonth.innerHTML += `
                                <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-medium text-gray-600 border border-gray-200">
                                            </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">${order.first_name} ${order.first_name}</div>
                                            <div class="text-xs text-gray-500">${order.email}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-100">${order.status}</span>
                                </td>
                                <td class="py-4 text-sm text-gray-500">${order.order_date}</td>
                                <td class="py-4 text-sm font-medium text-gray-900 text-right">${order.total_amount}</td>
                            </tr>
    
  `;
  }

  // Debug: Log the fetched data
  console.log("Fetched dataset:", dataset);

  // Robust validation
  if (
    !dataset ||
    typeof dataset !== "object" ||
    !dataset.success ||
    !Array.isArray(dataset.data)
  ) {
    console.error("Invalid data format");
    dataset.data = []; // Fallback to empty array
  }

  const orders = dataset.data;
  for (const order of orders) {
    ordersContainer.innerHTML += `

        <div class="space-y-6" id="ordersContainer">
                <!-- Transaction 1 -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium">T</div>
                            <div class="absolute -top-1 -left-1 w-4 h-4 bg-white rounded-full flex items-center justify-center border border-gray-50 shadow-sm">
                                <i class="ph ph-arrow-down-left text-[10px] text-green-500"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">${order.first_name} ${order.last_name}</p>
                            <p class="text-xs text-gray-400">${order.order_date}</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-emerald-500">${order.total_amount}</p>
                </div>
        `;
  }
  // Sort by order_date (parse to Date for comparison)
  orders.sort((a, b) => new Date(a.order_date) - new Date(b.order_date));

  // Extract labels (order dates) and data (total amounts)
  const labels = orders.map((order) => order.order_date); // e.g., ['2025-12-21 00:57:35', ...]
  const chartData = orders.map((order) => parseFloat(order.total_amount)); // e.g., [69.96, 37.98, 79.96]

  // Create the chart
  const myChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Total Order Amount",
          data: chartData,
          borderColor: "rgba(54, 162, 235, 1)",
          backgroundColor: "rgba(54, 162, 235, 0.2)",
          tension: 0.3,
        },
      ],
    },
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: "Order Date/Time",
          },
        },
        y: {
          title: {
            display: true,
            text: "Total Amount ($)",
          },
          beginAtZero: true,
        },
      },
    },
  });
});

async function fetchOrdersData() {
  try {
    const response = await fetch("dashboard.php?orders=1");
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    console.log("Parsed data:", data); // For debugging
    return data;
  } catch (error) {
    console.error("Fetch error:", error);
    return { success: false, data: [] }; // Fallback
  }
}
async function fetchUsersData() {
  try {
    const response = await fetch("dashboard.php?users=1");
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    console.log("Parsed data:", data); // For debugging
    return data;
  } catch (error) {
    console.error("Fetch error:", error);
    return { success: false, data: [] }; // Fallback
  }
}
async function fetchSalesPrevMonth() {
  try {
    const response = await fetch("dashboard.php?TotalSalesPrevMonth=1");
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    console.log("Parsed data:", data); // For debugging
    return data;
  } catch (error) {
    console.error("Fetch error:", error);
    return { success: false, data: [] }; // Fallback
  }
}

async function fetchSalesToday() {
  try {
    const response = await fetch("dashboard.php?TotalSalesToday=1");
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    console.log("Parsed data:", data); // For debugging
    return data;
  } catch (error) {
    console.error("Fetch error:", error);
    return { success: false, data: [] }; // Fallback
  }
}
async function fetchSalesPrevMonthDetailed() {
  try {
    const response = await fetch(`dashboard.php?TotalSalesPrevMonthDetailed=1`);
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    console.log("Parsed data:", data); // For debugging
    return data;
  } catch (error) {
    console.error("Fetch error:", error);
    return { success: false, data: [] }; // Fallback
  }
}

async function TotalSalesPrevOnAGivenDay(date) {
  try {
    const response = await fetch(
      `dashboard.php?TotalSalesPrevOnAGivenDay=1&date=${date}`
    );
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    console.log("Parsed data:", data); // For debugging
    return data;
  } catch (error) {
    console.error("Fetch error:", error);
    return { success: false, data: [] }; // Fallback
  }
}
