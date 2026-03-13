package domain

import "time"

type Vehicle struct {
	ID             string    `json:"id"`
	DriverName     string    `json:"driver_name"`
	Destination    string    `json:"destination"`
	DepartureTime  time.Time `json:"departure_time"`
	Status         string    `json:"status"` // e.g., "menunggu", "siap_berangkat", "sudah_berangkat"
}