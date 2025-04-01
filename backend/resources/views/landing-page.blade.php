<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FoundIt - Find your belongings!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800">
    <section class="max-w-7xl mx-auto px-6 py-12 md:py-20 flex flex-col-reverse md:flex-row items-center">
        <div class="w-full md:w-1/2 md:pr-8 mt-8 md:mt-0">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 leading-tight">
                Lost It? Don’t Stress, <br> Just
                <span class="text-purple-600"> FoundIt!</span>
            </h1>
            <p class="text-gray-600 text-lg md:text-xl mb-6">
                Use FoundIt to swiftly report lost items, receive real-time alerts,
                and securely reconnect with your belongings. Experience a smarter,
                community-powered solution anytime, anywhere.
            </p>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="#" class="inline-block px-6 py-3 bg-purple-600 text-white font-medium rounded-md hover:bg-purple-700 text-center">
                    Get Started
                </a>
                <a href="#" class="inline-block px-6 py-3 border border-purple-600 text-purple-600 font-medium rounded-md hover:bg-purple-50 text-center">
                    Watch Demo
                </a>
            </div>
            <div class="border-t-2 mt-5 max-w-7xl mx-auto px-6 py-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                <div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-purple-600">2k+</h3>
                    <p class="text-gray-600">Items Found</p>
                </div>
                <div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-purple-600">99%</h3>
                    <p class="text-gray-600">Return Rate</p>
                </div>
                <div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-purple-600">24H</h3>
                    <p class="text-gray-600">Avg Return Time</p>
                </div>
            </div>
        </div>

        <div class="relative w-full max-w-lg">
            <div class="bg-white rounded-xl shadow-lg p-6">

                <div class="flex items-start md:items-center justify-between bg-blue-50 rounded-md p-4 mb-4">
                    <div>
                        <h4 class="text-blue-600 font-semibold text-lg">Lost: Student ID Card</h4>
                        <p class="text-sm text-gray-600">Last seen: Library 3rd floor</p>
                    </div>
                    <span class="text-blue-500 font-medium mt-2 md:mt-0">
                        Match Found!
                    </span>
                </div>

                <div class="flex items-start md:items-center justify-between bg-green-50 rounded-md p-4 mb-4">
                    <div>
                        <h4 class="text-green-600 font-semibold text-lg">Found: Student ID Card</h4>
                        <p class="text-sm text-gray-600">Location: Library 3rd floor</p>
                    </div>
                    <span class="text-green-500 font-medium mt-2 md:mt-0">
                        Owner Notified
                    </span>
                </div>

                <div class="flex items-start md:items-center justify-between bg-red-50 rounded-md p-4">
                    <div>
                        <h4 class="text-red-500 font-semibold text-lg">Item Alert</h4>
                        <p class="text-sm text-gray-600">New match for your lost Student ID Card</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


</body>

</html>