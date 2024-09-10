<!-- src/pages/LandingPage.vue -->
<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <img class="mx-auto h-12 w-auto"
                    src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg"
                    alt="MarocConnect" />
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Welcome to MarocConnect</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Connect with friends, share moments, and explore Morocco together.
                </p>
            </div>

            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="space-y-6">
                    <p class="text-center text-sm text-gray-600">
                        Join our community today and start sharing your experiences!
                    </p>
                    <div class="flex justify-center">
                        <router-link to="/login"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Log In
                        </router-link>
                    </div>
                    <div class="flex justify-center">
                        <router-link to="/register"
                            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Sign Up
                        </router-link>
                    </div>
                    <div class="flex justify-center">
                        <router-link to="/home"
                            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Go to Home (Demo)
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'LandingPage'
}
</script>