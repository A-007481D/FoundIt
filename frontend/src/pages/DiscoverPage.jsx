

import { useState } from "react"
import { MapPin, Calendar, Eye, X, ArrowRight } from "lucide-react"
import "../styles/global.css"
import "../styles/components.css"
import "../styles/pages/discover.css"
// import FilterSidebar from "../components/common/FilterSidebar.jsx";

// Mock data for items
const featuredItems = [
    {
        id: "1",
        title: "iPhone 13 Pro - Space Gray",
        description:
            "Lost my iPhone at Central Park yesterday afternoon. It has a black case with a photo of my dog on the back.",
        image: "https://placehold.co/500?height=200&width=300",
        type: "lost",
        category: "Electronics",
        location: "Central Park, NY",
        date: "2 hours ago",
        views: 124,
        featured: true,
    },
    {
        id: "2",
        title: "Gold Ring with Diamond",
        description: "Found a gold ring with a small diamond near the subway entrance at Times Square station.",
        image: "https://placehold.co/400",
        type: "found",
        category: "Jewelry",
        location: "Times Square, NY",
        date: "5 hours ago",
        views: 87,
        featured: true,
    },
    {
        id: "3",
        title: "Brown Leather Wallet",
        description: "Lost my wallet somewhere between the coffee shop and the library. Contains ID and credit cards.",
        image: "src/assets/images/iphone.jpg?height=200&width=300",
        type: "lost",
        category: "Accessories",
        location: "Downtown, NY",
        date: "Yesterday",
        views: 56,
        featured: true,
    },
]

const recentItems = [
    {
        id: "4",
        title: "Car Keys with Red Keychain",
        description:
            "Lost my car keys with a distinctive red keychain and Volkswagen logo. Last seen at the shopping mall.",
        image: "https://placehold.co/400",
        type: "lost",
        category: "Keys",
        location: "Shopping Mall, NY",
        date: "3 hours ago",
        views: 42,
    },
    {
        id: "5",
        title: "Black Backpack - North Face",
        description: "Found a black North Face backpack at the park bench. Contains some books and a water bottle.",
        image: "https://placehold.co/400",
        type: "found",
        category: "Accessories",
        location: "City Park, NY",
        date: "4 hours ago",
        views: 31,
    },
    {
        id: "6",
        title: "Prescription Glasses",
        description:
            "Found prescription glasses with tortoise shell frames on the subway. Left them with the station attendant.",
        image: "https://placehold.co/400",
        type: "found",
        category: "Accessories",
        location: "Subway Station, NY",
        date: "6 hours ago",
        views: 28,
    },
]

function DiscoverPage() {
    const [activeTab, setActiveTab] = useState("all")
    const [showLocationPrompt, setShowLocationPrompt] = useState(true)

    return (
        <div className="discover-page">
            {/*<Navbar />*/}

            {showLocationPrompt && (
                <div className="location-prompt">
                    <div className="location-prompt-content">
                        <MapPin className="location-icon" />
                        <div className="location-text">
                            <p>Enable location services</p>
                            <p className="location-subtext">See lost & found items near you by enabling location access.</p>
                        </div>
                        <button className="close-button" onClick={() => setShowLocationPrompt(false)}>
                            <X size={16} />
                        </button>
                    </div>
                    <div className="location-actions">
                        <button className="button outline" onClick={() => setShowLocationPrompt(false)}>
                            Not now
                        </button>
                        <button className="button primary">Enable location</button>
                    </div>
                </div>
            )}

            <main className="discover-main">
                <div className="discover-container">
                    <div className="discover-header">
                        <h1 className="discover-title">Discover Items</h1>
                        <p className="discover-subtitle">Browse lost and found items in your area</p>
                    </div>

                    <div className="tabs-container">
                        <div className="tabs-header">
                            <div className="tabs-list">
                                <button
                                    className={`tab-button ${activeTab === "all" ? "active" : ""}`}
                                    onClick={() => setActiveTab("all")}
                                >
                                    All Items
                                </button>
                                <button
                                    className={`tab-button ${activeTab === "lost" ? "active" : ""}`}
                                    onClick={() => setActiveTab("lost")}
                                >
                                    Lost Items
                                </button>
                                <button
                                    className={`tab-button ${activeTab === "found" ? "active" : ""}`}
                                    onClick={() => setActiveTab("found")}
                                >
                                    Found Items
                                </button>
                            </div>
                            <div className="location-indicator">
                                <MapPin className="location-icon" />
                                <span className="location-text">New York, NY (5 mile radius)</span>
                            </div>
                        </div>

                        <div className="content-container">
                            {/* Filter sidebar - commented out as requested */}
                            {/* <FilterSidebar />*/}

                            <div className="items-container">
                                {activeTab === "all" && (
                                    <>
                                        <div className="featured-section">
                                            <div className="section-header">
                                                <h2 className="section-title">Featured Items</h2>
                                                <button className="view-all-button">
                                                    View all <ArrowRight className="arrow-icon" />
                                                </button>
                                            </div>
                                            <div className="items-grid">
                                                {featuredItems.map((item) => (
                                                    <ItemCard key={item.id} item={item} />
                                                ))}
                                            </div>
                                        </div>

                                        <div className="recent-section">
                                            <div className="section-header">
                                                <h2 className="section-title">Recent Items</h2>
                                                <button className="view-all-button">
                                                    View all <ArrowRight className="arrow-icon" />
                                                </button>
                                            </div>
                                            <div className="items-grid">
                                                {recentItems.map((item) => (
                                                    <ItemCard key={item.id} item={item} />
                                                ))}
                                            </div>
                                        </div>
                                    </>
                                )}

                                {activeTab === "lost" && (
                                    <div className="items-grid">
                                        {[...featuredItems, ...recentItems]
                                            .filter((item) => item.type === "lost")
                                            .map((item) => (
                                                <ItemCard key={item.id} item={item} />
                                            ))}
                                    </div>
                                )}

                                {activeTab === "found" && (
                                    <div className="items-grid">
                                        {[...featuredItems, ...recentItems]
                                            .filter((item) => item.type === "found")
                                            .map((item) => (
                                                <ItemCard key={item.id} item={item} />
                                            ))}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    )
}

function ItemCard({ item }) {
    return (
        <div className={`item-card ${item.featured ? "featured" : ""}`}>
            <div className="item-image-container">
                <img src={item.image || "/placeholder.svg"} alt={item.title} className="item-image" />
                <div className={`item-badge ${item.type}`}>{item.type === "lost" ? "Lost" : "Found"}</div>
                {item.featured && <div className="featured-badge">Featured</div>}
            </div>
            <div className="item-content">
                <div className="item-header">
                    <h3 className="item-title">{item.title}</h3>
                    <span className="item-category">{item.category}</span>
                </div>
                <p className="item-description">{item.description}</p>
                <div className="item-meta">
                    <div className="item-location">
                        <MapPin className="meta-icon" />
                        <span>{item.location}</span>
                    </div>
                    <div className="item-date">
                        <Calendar className="meta-icon" />
                        <span>{item.date}</span>
                    </div>
                    <div className="item-views">
                        <Eye className="meta-icon" />
                        <span>{item.views}</span>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default DiscoverPage

