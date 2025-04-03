import React, { useState } from 'react';
import { MapPin, X } from 'lucide-react';

function LocationPermission() {
    const [isVisible, setIsVisible] = useState(true);
    const [permissionState, setPermissionState] = useState("prompt");
    const [locationName, setLocationName] = useState(null);

    const handleRequestLocation = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    setPermissionState("granted");
                    // In a real app, you would use a geocoding service API
                    setLocationName("New York, NY");
                },
                (error) => {
                    console.error("Error getting location:", error);
                    setPermissionState("denied");
                }
            );
        } else {
            setPermissionState("unsupported");
        }
    };

    const handleDismiss = () => {
        setIsVisible(false);
    };

    if (!isVisible || (permissionState === "granted" && locationName)) {
        return (
            <div className="fixed bottom-4 left-4 z-40 flex items-center gap-2 rounded-full bg-purple-600 px-4 py-2 text-white shadow-lg">
                <MapPin className="h-4 w-4" />
                <span className="text-sm font-medium">{locationName || "Location enabled"}</span>
            </div>
        );
    }

    if (!isVisible) return null;

    return (
        <div className="fixed bottom-4 left-0 right-0 z-40 mx-auto w-full max-w-md rounded-lg border bg-white p-4 shadow-lg md:bottom-8">
            <div className="flex items-start justify-between">
                <div className="flex items-start gap-3">
                    <div className="rounded-full bg-purple-100 p-2">
                        <MapPin className="h-5 w-5 text-purple-600" />
                    </div>
                    <div>
                        <h3 className="font-medium">Enable location services</h3>
                        <p className="mt-1 text-sm text-gray-500">
                            {permissionState === "unsupported"
                                ? "Your browser doesn't support geolocation."
                                : "See lost & found items near you by enabling location access."}
                        </p>
                    </div>
                </div>
                <button className="text-gray-400 hover:text-gray-500" onClick={handleDismiss}>
                    <X className="h-4 w-4" />
                    <span className="sr-only">Dismiss</span>
                </button>
            </div>
            {permissionState !== "unsupported" && (
                <div className="mt-4 flex gap-2">
                    <button
                        onClick={handleDismiss}
                        className="flex-1 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Not now
                    </button>
                    <button
                        onClick={handleRequestLocation}
                        className="flex-1 rounded-md bg-purple-600 px-3 py-2 text-sm font-medium text-white hover:bg-purple-700"
                    >
                        Enable location
                    </button>
                </div>
            )}
        </div>
    );
}

export default LocationPermission;