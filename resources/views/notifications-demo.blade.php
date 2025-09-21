<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification System Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8 text-blue-600">Smart Notification System</h1>
        <p class="text-center text-gray-600 mb-8">Demonstrating Laravel Service Container & Service Providers</p>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Send Notification Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Send Notification</h2>
                <form id="notificationForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Message</label>
                        <textarea name="message" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your notification message..." rows="3">Hello! This is a test notification from our smart system.</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Channels (optional)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="channels[]" value="email" class="mr-2" checked>
                                <span class="text-sm">Email</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="channels[]" value="sms" class="mr-2">
                                <span class="text-sm">SMS</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="channels[]" value="slack" class="mr-2" checked>
                                <span class="text-sm">Slack</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="channels[]" value="database" class="mr-2" checked>
                                <span class="text-sm">Database</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Additional Data</label>
                        <input type="email" name="email" placeholder="Email address"
                            class="w-full px-3 py-2 border rounded-lg mb-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <input type="text" name="phone" placeholder="Phone number"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                        Send Notification
                    </button>
                </form>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">System Information</h2>
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Available Channels</h3>
                    <div id="channelsInfo" class="text-sm text-gray-600">Loading...</div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Configuration</h3>
                    <div id="configInfo" class="text-sm text-gray-600">Loading...</div>
                </div>

                <button onclick="loadSystemInfo()"
                    class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-200">
                    Refresh Info
                </button>

                <button onclick="runTest()"
                    class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200 ml-2">
                    Run Test
                </button>
            </div>
        </div>

        <!-- Results Display -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Results</h2>
            <div id="results" class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500 text-center">Results will appear here...</p>
            </div>
        </div>

        <!-- Explanation -->
        <div class="mt-8 bg-blue-50 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 text-blue-800">How It Works</h2>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">Service Providers</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Register bindings in container</li>
                        <li>Configure notification channels</li>
                        <li>Handle dependency resolution</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Service Container</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Manages object creation</li>
                        <li>Handles dependency injection</li>
                        <li>Resolves interfaces to implementations</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Benefits</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Easy to add new channels</li>
                        <li>Configurable via config files</li>
                        <li>Loose coupling between components</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load system info on page load
        document.addEventListener('DOMContentLoaded', loadSystemInfo);

        async function loadSystemInfo() {
            try {
                const response = await fetch('/notifications/channels');
                const data = await response.json();

                document.getElementById('channelsInfo').innerHTML = `
                    <div class="flex flex-wrap gap-1">
                        ${data.available_channels.map(channel => `
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">${channel}</span>
                        `).join('')}
                    </div>
                `;

                document.getElementById('configInfo').innerHTML = `
                    <div>Default channels: ${data.configuration.default_channels.join(', ')}</div>
                    <div class="mt-1">Max retries: ${data.configuration.max_retries}</div>
                `;
            } catch (error) {
                console.error('Error loading system info:', error);
            }
        }

        async function runTest() {
            try {
                const response = await fetch('/notifications/test');
                const data = await response.json();
                showResults(data);
            } catch (error) {
                console.error('Error running test:', error);
            }
        }

        document.getElementById('notificationForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            // Convert channels array properly
            if (data.channels) {
                data.channels = Array.isArray(data.channels) ? data.channels : [data.channels];
            }

            try {
                const response = await fetch('/notifications/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                showResults(result);
            } catch (error) {
                console.error('Error sending notification:', error);
            }
        });

        function showResults(data) {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <pre class="text-sm bg-white p-4 rounded border">${JSON.stringify(data, null, 2)}</pre>
            `;
        }
    </script>
</body>
</html>
