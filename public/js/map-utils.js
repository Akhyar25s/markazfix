/**
 * Leaflet Map Utilities for Markaz Fix
 * Consistent Satellite + Labels implementation
 */

window.MarkazMap = {
    // Default tiles configuration
    tiles: {
        satellite: {
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            options: {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EBP, and the GIS User Community',
                maxZoom: 20
            }
        },
        labels: {
            url: 'https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png',
            options: {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20,
                pane: 'markerPane'
            }
        }
    },

    /**
     * Initialize a standard Markaz map
     * @param {string} elementId - ID of the div element
     * @param {Array} center - [lat, lng]
     * @param {number} zoom - initial zoom level
     * @returns {L.Map}
     */
    init: function(elementId, center = [-2.5489, 118.0149], zoom = 5) {
        const map = L.map(elementId).setView(center, zoom);
        
        L.tileLayer(this.tiles.satellite.url, this.tiles.satellite.options).addTo(map);
        L.tileLayer(this.tiles.labels.url, this.tiles.labels.options).addTo(map);
        
        return map;
    },

    /**
     * Create a standard marker icon
     * @param {string} colorClass - Tailwind color class (e.g., 'bg-primary')
     * @param {string} svgContent - Inner SVG content
     * @returns {L.DivIcon}
     */
    createIcon: function(colorClass = 'bg-primary', svgContent = '') {
        if (!svgContent) {
            svgContent = `<svg fill="currentColor" viewBox="0 0 24 24" class="h-6 w-6"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"></path></svg>`;
        }
        
        return L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="${colorClass} text-primary-foreground w-10 h-10 rounded-full flex items-center justify-center shadow-lg border-2 border-background transform scale-110 transition-transform hover:scale-125">
                    ${svgContent}
                   </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });
    },

    /**
     * Create a standardized geofence circle
     * @param {Array} latlng - [lat, lng]
     * @param {number} radius - radius in meters
     * @returns {L.Circle}
     */
    createGeofence: function(latlng, radius = 100) {
        return L.circle(latlng, {
            color: 'hsl(var(--primary))',
            fillColor: 'hsl(var(--primary))',
            fillOpacity: 0.2,
            radius: radius,
            weight: 2
        });
    }
};
