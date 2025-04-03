import { useState } from "react"
import { motion } from "framer-motion"
import { ArrowLeft, Mail, MapPin, Send } from "lucide-react"
import '../../styles/auth.css';

const ForgotPassword = () => {
    const [email, setEmail] = useState("")
    const [isLoading, setIsLoading] = useState(false)
    const [isSubmitted, setIsSubmitted] = useState(false)

    const handleSubmit = async (e) => {
        e.preventDefault()
        setIsLoading(true)

            // // Simulate API call
            // setTimeout(() => {
            //     setIsLoading(false)
            //     setIsSubmitted(true)
            // }, 1500)
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
                                <div className="flex items-center mb-4">
                                    <a href="/login" className="text-gray-500 hover:text-gray-700">
                                        <ArrowLeft className="h-4 w-4" />
                                    </a>
                                </div>
                                <div className="space-y-1 mb-6">
                                    <h2 className="text-2xl font-bold text-center text-gray-900">Mot de passe oublié</h2>
                                    <p className="text-center text-gray-600">
                                        Entrez votre adresse email pour réinitialiser votre mot de passe
                                    </p>
                                </div>

                                <div className="space-y-4">
                                    {isSubmitted ? (
                                        <motion.div
                                            initial={{ opacity: 0, scale: 0.95 }}
                                            animate={{ opacity: 1, scale: 1 }}
                                            transition={{ duration: 0.3 }}
                                        >
                                            <div className="bg-blue-50 border border-blue-200 rounded-md p-4 flex items-start">
                                                <Mail className="h-5 w-5 text-blue-500 mt-0.5 mr-3" />
                                                <div>
                                                    <h3 className="font-medium text-blue-800">Email envoyé!</h3>
                                                    <p className="text-blue-700 mt-1">
                                                        Si un compte existe avec l'adresse {email}, vous recevrez un email avec les instructions
                                                        pour réinitialiser votre mot de passe.
                                                    </p>
                                                </div>
                                            </div>
                                            <div className="mt-6 text-center">
                                                <a href="/login" className="text-blue-600 hover:underline">
                                                    Retour à la page de connexion
                                                </a>
                                            </div>
                                        </motion.div>
                                    ) : (
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
                                                        Envoi en cours...
                                                    </div>
                                                ) : (
                                                    <div className="flex items-center">
                                                        <Send className="mr-2 h-4 w-4" />
                                                        Envoyer les instructions
                                                    </div>
                                                )}
                                            </button>
                                        </form>
                                    )}
                                </div>
                            </div>
                            <div className="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                {!isSubmitted && (
                                    <p className="text-center text-sm text-gray-600">
                                        Vous vous souvenez de votre mot de passe?{" "}
                                        <a href="/login" className="text-blue-600 hover:underline font-medium">
                                            Se connecter
                                        </a>
                                    </p>
                                )}
                            </div>
                        </div>
                    </motion.div>
                </div>
            </main>
        </div>
    )
}

export default ForgotPassword

