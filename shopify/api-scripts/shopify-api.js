/**
 * Shopify Admin GraphQL API Integration Script
 * Demonstrates:
 * 1. Fetching products list, variant details, pricing, and stock inventory quantities.
 * 2. Mutation (productCreate) to add a new product item.
 * 3. Mutation (inventoryAdjustQuantities) to adjust stock inventory counts.
 * 4. Query orders data and print a sales report.
 */

const https = require('https');

// Read from Environment Variables or use fallbacks
const SHOP_URL = process.env.SHOPIFY_SHOP_URL || 'fitwear-store.myshopify.com';
const ACCESS_TOKEN = process.env.SHOPIFY_ACCESS_TOKEN || 'shpat_mock_token_12345';
const IS_MOCK_MODE = ACCESS_TOKEN.startsWith('shpat_mock_');

/**
 * Main Execution Flow
 */
async function run() {
  console.log('==================================================');
  console.log(`Starting Shopify API Integration Script`);
  console.log(`Target Shop: ${SHOP_URL}`);
  console.log(`Execution Mode: ${IS_MOCK_MODE ? 'MOCK / DEMO MODE' : 'LIVE API MODE'}`);
  console.log('==================================================\n');

  try {
    // 1. Fetch product inventory lists
    console.log('--- 1. Fetching Products & Inventory Levels ---');
    const products = await fetchProductsInventory();
    printProductReport(products);
    console.log('\n');

    // 2. Create a new clothing product
    console.log('--- 2. Creating a New Product (FitWear Active T-Shirt) ---');
    const newProduct = await createProduct({
      title: 'FitWear Elite Active T-Shirt',
      bodyHtml: '<strong>Premium lightweight fabric</strong> designed for maximum breathability.',
      vendor: 'FitWear',
      productType: 'Athletic Wear',
      price: '28.00',
      sku: 'FW-TEE-ELITE-01'
    });
    console.log(`New Product Created: ID: ${newProduct.id} | Title: ${newProduct.title}`);
    console.log('\n');

    // 3. Update variant inventory levels
    console.log('--- 3. Updating Inventory Stock levels ---');
    const inventoryItemId = newProduct.inventoryItemId || 'gid://shopify/InventoryItem/12345678';
    const updatedInventory = await adjustInventoryQuantity(inventoryItemId, 50); // Add 50 units
    console.log(`Stock adjusted successfully. New count: ${updatedInventory.available}`);
    console.log('\n');

    // 4. Fetch order data and output sales reports
    console.log('--- 4. Retrieving Order Data & Generating Report ---');
    const orders = await fetchOrderData();
    generateSalesReport(orders);

  } catch (error) {
    console.error('An error occurred during API execution:', error.message);
  }

  console.log('\n==================================================');
  console.log('Shopify API Integration Script Complete.');
  console.log('==================================================');
}

/**
 * GraphQL Query: Fetch Products & Inventory Levels
 */
async function fetchProductsInventory() {
  const query = `
    query {
      products(first: 5) {
        edges {
          node {
            id
            title
            productType
            variants(first: 5) {
              edges {
                node {
                  id
                  title
                  price
                  sku
                  inventoryItem {
                    id
                  }
                  inventoryQuantity
                }
              }
            }
          }
        }
      }
    }
  `;

  if (IS_MOCK_MODE) {
    // Return mock query response
    return [
      {
        id: 'gid://shopify/Product/762512412',
        title: 'FitWear Leggings',
        productType: 'Bottoms',
        variants: [
          { id: 'gid://shopify/ProductVariant/112', title: 'S', price: '55.00', sku: 'FW-LEG-S-01', inventoryQuantity: 12 },
          { id: 'gid://shopify/ProductVariant/113', title: 'M', price: '55.00', sku: 'FW-LEG-M-01', inventoryQuantity: 28 },
          { id: 'gid://shopify/ProductVariant/114', title: 'L', price: '55.00', sku: 'FW-LEG-L-01', inventoryQuantity: 5 }
        ]
      },
      {
        id: 'gid://shopify/Product/762512415',
        title: 'FitWear Active Tee',
        productType: 'Tops',
        variants: [
          { id: 'gid://shopify/ProductVariant/212', title: 'M', price: '25.00', sku: 'FW-TEE-M-01', inventoryQuantity: 45 },
          { id: 'gid://shopify/ProductVariant/213', title: 'L', price: '25.00', sku: 'FW-TEE-L-01', inventoryQuantity: 0 }
        ]
      }
    ];
  }

  const response = await shopifyGraphQLRequest(query);
  return response.data.products.edges.map(edge => ({
    id: edge.node.id,
    title: edge.node.title,
    productType: edge.node.productType,
    variants: edge.node.variants.edges.map(vEdge => ({
      id: vEdge.node.id,
      title: vEdge.node.title,
      price: vEdge.node.price,
      sku: vEdge.node.sku,
      inventoryItemId: vEdge.node.inventoryItem.id,
      inventoryQuantity: vEdge.node.inventoryQuantity
    }))
  }));
}

/**
 * GraphQL Mutation: Create a New Product
 */
async function createProduct(details) {
  const mutation = `
    mutation productCreate($input: ProductInput!) {
      productCreate(input: $input) {
        product {
          id
          title
          variants(first: 1) {
            edges {
              node {
                id
                inventoryItem {
                  id
                }
              }
            }
          }
        }
        userErrors {
          field
          message
        }
      }
    }
  `;

  const variables = {
    input: {
      title: details.title,
      bodyHtml: details.bodyHtml,
      vendor: details.vendor,
      productType: details.productType,
      variants: [{
        price: details.price,
        sku: details.sku
      }]
    }
  };

  if (IS_MOCK_MODE) {
    // Return mock mutation response
    return {
      id: 'gid://shopify/Product/999123881',
      title: details.title,
      inventoryItemId: 'gid://shopify/InventoryItem/999888777'
    };
  }

  const response = await shopifyGraphQLRequest(mutation, variables);
  if (response.errors || response.data.productCreate.userErrors.length > 0) {
    throw new Error('Failed to create product: ' + JSON.stringify(response));
  }

  const productNode = response.data.productCreate.product;
  const firstVariant = productNode.variants.edges[0].node;
  return {
    id: productNode.id,
    title: productNode.title,
    inventoryItemId: firstVariant.inventoryItem.id
  };
}

/**
 * GraphQL Mutation: Adjust Stock Quantity
 */
async function adjustInventoryQuantity(inventoryItemId, adjustment) {
  // First we need to get the active inventory level location ID
  // For demonstration, we assume we fetch or have a primary location ID
  const locationId = 'gid://shopify/Location/77665544';

  const mutation = `
    mutation inventoryAdjustQuantities($input: InventoryAdjustQuantitiesInput!) {
      inventoryAdjustQuantities(input: $input) {
        inventoryAdjustmentGroup {
          createdAt
        }
        userErrors {
          field
          message
        }
      }
    }
  `;

  const variables = {
    input: {
      reason: "correction",
      name: "available",
      changes: [{
        inventoryItemId: inventoryItemId,
        locationId: locationId,
        delta: adjustment
      }]
    }
  };

  if (IS_MOCK_MODE) {
    // Return mock mutation response
    return {
      available: 50
    };
  }

  const response = await shopifyGraphQLRequest(mutation, variables);
  if (response.errors || response.data.inventoryAdjustQuantities.userErrors.length > 0) {
    throw new Error('Failed to adjust inventory: ' + JSON.stringify(response));
  }

  return {
    available: adjustment
  };
}

/**
 * GraphQL Query: Fetch Order Data
 */
async function fetchOrderData() {
  const query = `
    query {
      orders(first: 10, sortKey: CREATED_AT, reverse: true) {
        edges {
          node {
            id
            name
            createdAt
            totalPriceSet {
              shopMoney {
                amount
                currencyCode
              }
            }
            lineItems(first: 5) {
              edges {
                node {
                  title
                  quantity
                }
              }
            }
          }
        }
      }
    }
  `;

  if (IS_MOCK_MODE) {
    // Return mock order lists
    return [
      {
        id: 'gid://shopify/Order/888123',
        name: '#FW-1001',
        createdAt: '2026-06-20T14:32:00Z',
        totalPrice: '145.00',
        currency: 'USD',
        lineItems: [
          { title: 'FitWear Leggings', quantity: 2 },
          { title: 'FitWear Active T-Shirt', quantity: 1 }
        ]
      },
      {
        id: 'gid://shopify/Order/888124',
        name: '#FW-1002',
        createdAt: '2026-06-21T09:12:00Z',
        totalPrice: '25.00',
        currency: 'USD',
        lineItems: [
          { title: 'FitWear Active Tee', quantity: 1 }
        ]
      }
    ];
  }

  const response = await shopifyGraphQLRequest(query);
  return response.data.orders.edges.map(edge => ({
    id: edge.node.id,
    name: edge.node.name,
    createdAt: edge.node.createdAt,
    totalPrice: edge.node.totalPriceSet.shopMoney.amount,
    currency: edge.node.totalPriceSet.shopMoney.currencyCode,
    lineItems: edge.node.lineItems.edges.map(li => ({
      title: li.node.title,
      quantity: li.node.quantity
    }))
  }));
}

/**
 * Fetch HTTP Helper using node https module
 */
function shopifyGraphQLRequest(query, variables = {}) {
  return new Promise((resolve, reject) => {
    const data = JSON.stringify({ query, variables });
    
    const options = {
      hostname: SHOP_URL,
      path: '/admin/api/2023-10/graphql.json',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Shopify-Access-Token': ACCESS_TOKEN,
        'Content-Length': data.length
      }
    };

    const req = https.request(options, (res) => {
      let body = '';
      res.on('data', chunk => body += chunk);
      res.on('end', () => {
        try {
          resolve(JSON.parse(body));
        } catch (e) {
          reject(new Error('Invalid JSON response: ' + body));
        }
      });
    });

    req.on('error', (err) => reject(err));
    req.write(data);
    req.end();
  });
}

/**
 * Report Printing helper
 */
function printProductReport(products) {
  console.log('--------------------------------------------------');
  console.log('PRODUCT INVENTORY REPORT');
  console.log('--------------------------------------------------');
  products.forEach(p => {
    console.log(`Product: ${p.title} (${p.productType})`);
    p.variants.forEach(v => {
      const stockIndicator = v.inventoryQuantity === 0 ? 'OUT OF STOCK' : `${v.inventoryQuantity} units`;
      console.log(`  - Variant: ${v.title} | Price: $${v.price} | SKU: ${v.sku} | Stock: ${stockIndicator}`);
    });
  });
  console.log('--------------------------------------------------');
}

/**
 * Sales Report Generating helper
 */
function generateSalesReport(orders) {
  console.log('--------------------------------------------------');
  console.log('SALES & ORDERS REPORT');
  console.log('--------------------------------------------------');
  let totalSales = 0;
  orders.forEach(o => {
    console.log(`Order: ${o.name} | Date: ${o.createdAt.substring(0,10)} | Value: $${o.totalPrice} ${o.currency}`);
    o.lineItems.forEach(item => {
      console.log(`  - ${item.quantity}x ${item.title}`);
    });
    totalSales += parseFloat(o.totalPrice);
  });
  console.log('--------------------------------------------------');
  console.log(`Total Sales Value: $${totalSales.toFixed(2)} USD`);
  console.log(`Total Orders Processed: ${orders.length}`);
  console.log('--------------------------------------------------');
}

// Execute Script
run();
