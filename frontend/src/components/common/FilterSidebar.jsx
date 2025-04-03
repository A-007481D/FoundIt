import React, { useState } from 'react';
import { Filter } from 'lucide-react';

function FilterSidebar({ className, onFilterChange }) {
    const [distance, setDistance] = useState(5);
    const [showMobileFilters, setShowMobileFilters] = useState(false);

    const categories = [
        { id: "electronics", label: "Electronics" },
        { id: "jewelry", label: "Jewelry" },
        { id: "pets", label: "Pets" },
        { id: "documents", label: "Documents" },
        { id: "clothing", label: "Clothing" },
        { id: "accessories", label: "Accessories" },
        { id: "keys", label: "Keys" },
        { id: "other", label: "Other" },
    ];

    const handleDistanceChange = (e) => {
        setDistance(e.target.value);
    };

    const filterContent = (
        <div className="flex flex-col gap-6">
            <div>
                <h3 className="mb-2 font-medium">Item Type</h3>
                <div className="space-y-2">
                    <div className="flex items-center space-x-2">
                        <input type="radio" id="all" name="itemType" value="all" defaultChecked />
                        <label htmlFor="all">All Items</label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <input type="radio" id="lost" name="itemType" value="lost" />
                        <label htmlFor="lost">Lost Items</label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <input type="radio" id="found" name="itemType" value="found" />
                        <label htmlFor="found">Found Items</label>
                    </div>
                </div>
            </div>

            <div>
                <h3 className="mb-2 font-medium">Categories</h3>
                <div className="space-y-2">
                    {categories.map((category) => (
                        <div key={category.id} className="flex items-center space-x-2">
                            <input type="checkbox" id={category.id} />
                            <label htmlFor={category.id}>{category.label}</label>
                        </div>
                    ))}
                </div>
            </div>

            <div>
                <h3 className="mb-2 font-medium">Distance (miles)</h3>
                <input
                    type="range"
                    min="1"
                    max="50"
                    step="1"
                    value={distance}
                    onChange={handleDistanceChange}
                    className="w-full accent-purple-600"
                />
                <div className="flex items-center justify-between">
                    <span className="text-sm text-gray-500">1 mile</span>
                    <span className="font-medium">{distance} miles</span>
                    <span className="text-sm text-gray-500">50 miles</span>
                </div>
            </div>

            <div>
                <h3 className="mb-2 font-medium">Date Posted</h3>
                <div className="space-y-2">
                    <div className="flex items-center space-x-2">
                        <input type="radio" id="anytime" name="datePosted" value="anytime" defaultChecked />
                        <label htmlFor="anytime">Anytime</label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <input type="radio" id="today" name="datePosted" value="today" />
                        <label htmlFor="today">Today</label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <input type="radio" id="this-week" name="datePosted" value="this-week" />
                        <label htmlFor="this-week">This Week</label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <input type="radio" id="this-month" name="datePosted" value="this-month" />
                        <label htmlFor="this-month">This Month</label>
                    </div>
                </div>
            </div>

            <button className="mt-2 rounded-md bg-purple-600 px-4 py-2 font-medium text-white hover:bg-purple-700">
                Apply Filters
            </button>
        </div>
    );

    return (
        <>
             {/*Desktop sidebar - commented out as requested */}
             <div className={`hidden w-64 shrink-0 md:block ${className || ""}`}>
        {filterContent}
      </div>

             {/*Mobile filter button - commented out as requested */}
             <div className="fixed bottom-20 right-4 z-40 md:hidden">
        <button
          className="flex h-12 w-12 items-center justify-center rounded-full bg-purple-600 shadow-lg text-white"
          onClick={() => setShowMobileFilters(true)}
        >
          <Filter className="h-5 w-5" />
          <span className="sr-only">Filters</span>
        </button>
      </div>

             {/*Mobile filter sheet - commented out as requested */}
             {showMobileFilters && (
        <div className="fixed inset-0 z-50 flex items-end bg-black bg-opacity-50 md:hidden">
          <div className="h-[80vh] w-full rounded-t-xl bg-white p-4">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-semibold">Filters</h2>
              <button
                onClick={() => setShowMobileFilters(false)}
                className="rounded-full p-1 hover:bg-gray-100"
              >
                <X className="h-5 w-5" />
              </button>
            </div>
            {filterContent}
          </div>
        </div>
      )}
        </>
    );
}

export default FilterSidebar;

