import { useState } from "react"
import { motion } from "framer-motion"
import { Eye, EyeOff, Facebook, Lock, LogIn, Mail, MapPin } from 'lucide-react'

const Login = () => {
    const [showPassword, setShowPassword] = useState(false)
    const [email, setEmail] = useState("")
    const [password, setPassword] = useState("")
    const [isLoading, setIsLoading] = useState(false)

    const handleSubmit = async (e) => {
        e.preventDefault()
        setIsLoading(true)



    }

    return (
        <div className="min-h-screen bg-gray-50 flex flex-col">
            {/*/!* Header *!/*/}
            {/*<header className="w-full border-b bg-white shadow-sm">*/}
            {/*    <div className="container mx-auto px-4 flex h-16 items-center">*/}
            {/*        <a href="/" className="flex items-center gap-2 font-bold">*/}
            {/*            <div className="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">*/}
            {/*                <MapPin className="h-4 w-4" />*/}
            {/*            </div>*/}
            {/*            <span className="text-xl">FoundIt!</span>*/}
            {/*        </a>*/}
            {/*    </div>*/}
            {/*</header>*/}

            {/* Main content */}
            <main className="flex-1 flex items-center justify-center p-4 md:p-8">
                <div className="w-full max-w-md">
                    <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }}>
                        <div className="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                            <div className="p-6">
                                <div className="space-y-1 mb-6">
                                    <h2 className="text-2xl font-bold text-center text-gray-900">Connexion</h2>
                                    <p className="text-center text-gray-600">Entrez vos identifiants pour accéder à votre compte</p>
                                </div>

                                <div className="space-y-4">
                                    {/* Social Login Buttons */}
                                    <div className="grid grid-cols-2 gap-4">
                                        <button
                                            className="w-full flex items-center justify-center gap-2 py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            type="button">
                                            <svg className="h-4 w-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z"
                                                    fill="#4285F4" />

                                                <path d="M12.956 16.455h-3.956v-3.956h3.956v3.956z" fill="#34A853" />
                                                <path d="M12.956 8.544h-3.956v3.956h3.956v-3.956z" fill="#FBBC05" />
                                                <path d="M8.999 12.5h3.957v3.955h-3.957z" fill="#EA4335" />
                                            </svg>
                                            Google
                                        </button>
                                        <button
                                            className="w-full flex items-center justify-center gap-2 py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            type="button">
                                            <Facebook className="h-4 w-4 text-blue-600" />
                                            Facebook
                                        </button>
                                    </div>

                                    <div className="relative">
                                        <div className="absolute inset-0 flex items-center">
                                            <span className="w-full border-t border-gray-300" />
                                        </div>
                                        <div className="relative flex justify-center text-xs uppercase">
                                            <span className="bg-white px-2 text-gray-500">Ou continuez avec</span>
                                        </div>
                                    </div>

                                    <form onSubmit={handleSubmit} className="space-y-4">
                                        <div className="space-y-2">
                                            <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                                Email
                                            </label>
                                            <div className="relative">
                                                <Mail className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                                <input
                                                    id="email"
                                                    placeholder="nom@exemple.com"
                                                    type="email"
                                                    className="pl-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    value={email}
                                                    onChange={(e) => setEmail(e.target.value)}
                                                    required
                                                />
                                            </div>
                                        </div>
                                        <div className="space-y-2">
                                            <div className="flex items-center justify-between">
                                                <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                                                    Mot de passe
                                                </label>
                                                <a href="/forgot-password" className="text-xs text-blue-600 hover:underline">
                                                    Mot de passe oublié?
                                                </a>
                                            </div>
                                            <div className="relative">
                                                <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                                <input
                                                    id="password"
                                                    type={showPassword ? "text" : "password"}
                                                    className="pl-12 pr-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="••••••••"
                                                    value={password}
                                                    onChange={(e) => setPassword(e.target.value)}
                                                    required
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => setShowPassword(!showPassword)}
                                                    className="absolute right-3 top-3 text-gray-400 hover:text-gray-600"
                                                >
                                                    {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                                                </button>
                                            </div>
                                        </div>
                                        <div className="flex items-center space-x-2">
                                            <input
                                                type="checkbox"
                                                id="remember"
                                                className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            />
                                            <label htmlFor="remember" className="text-sm font-medium text-gray-700">
                                                Se souvenir de moi
                                            </label>
                                        </div>
                                        <button
                                            type="submit"
                                            className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            disabled={isLoading}
                                        >
                                            {isLoading ? (
                                                <div className="flex items-center">
                                                    <svg
                                                        className="animate-spin -ml-1 mr-3 h-4 w-4 text-white"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <circle
                                                            className="opacity-25"
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                            stroke="currentColor"
                                                            strokeWidth="4"
                                                        ></circle>
                                                        <path
                                                            className="opacity-75"
                                                            fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                        ></path>
                                                    </svg>
                                                    Connexion en cours...
                                                </div>
                                            ) : (
                                                <div className="flex items-center">
                                                    <LogIn className="mr-2 h-4 w-4" />
                                                    Se connecter
                                                </div>
                                            )}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div className="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                <p className="text-center text-sm text-gray-600">
                                    Pas encore de compte?{" "}
                                    <a href="/register" className="text-blue-600 hover:underline font-medium">
                                        Créer un compte
                                    </a>
                                </p>
                            </div>
                        </div>
                    </motion.div>
                </div>
            </main>
        </div>
    )
}

export default Login
