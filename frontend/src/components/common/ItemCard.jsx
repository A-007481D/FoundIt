import React from 'react';
import { MapPin, Calendar, Eye } from 'lucide-react';

function ItemCard({ id, title, description, image, type, category, location, date, views, featured }) {
    return (
        <a href={`/items/${id}`} className="block h-full">
            <div
                className={`group h-full overflow-hidden rounded-lg border border-gray-200 bg-white transition-all hover:shadow-md ${
                    featured ? "ring-2 ring-purple-500 ring-offset-2" : ""
                }`}
            >
                <div className="relative aspect-video overflow-hidden">
                    <img
                        src={image || "/assets/images/placeholder.jpg"}
                        alt={title}
                        className="h-full w-full object-cover transition-transform group-hover:scale-105"
                    />
                    <div className="absolute left-2 top-2 z-10">
            <span
                className={`inline-flex rounded-full px-2 py-1 text-xs font-medium ${
                    type === "lost"
                        ? "bg-red-100 text-red-800"
                        : "bg-green-100 text-green-800"
                }`}
            >
              {type === "lost" ? "Lost" : "Found"}
            </span>
                    </div>
                    {featured && (
                        <div className="absolute right-2 top-2 z-10">
              <span className="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-800">
                Featured
              </span>
                        </div>
                    )}
                </div>
                <div className="p-4 pb-0">
                    <div className="flex items-start justify-between">
                        <h3 className="line-clamp-1 font-semibold">{title}</h3>
                        <span className="ml-2 shrink-0 rounded-full border border-gray-200 px-2 py-0.5 text-xs">
              {category}
            </span>
                    </div>
                </div>
                <div className="p-4 pt-2">
                    <p className="line-clamp-2 text-sm text-gray-500">{description}</p>
                </div>
                <div className="flex items-center justify-between p-4 pt-0 text-xs text-gray-500">
                    <div className="flex items-center gap-1">
                        <MapPin className="h-3 w-3" />
                        <span>{location}</span>
                    </div>
                    <div className="flex items-center gap-1">
                        <Calendar className="h-3 w-3" />
                        <span>{date}</span>
                    </div>
                    <div className="flex items-center gap-1">
                        <Eye className="h-3 w-3" />
                        <span>{views}</span>
                    </div>
                </div>
            </div>
        </a>
    );
}

export default ItemCard;