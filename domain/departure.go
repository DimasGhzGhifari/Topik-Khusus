package domain

import "time"

type Departure struct {
	ID            string    `json:"id"`
	VehicleID     string    `json:"vehicle_id"`
	QueuePosition int       `json:"queue_position"`
	ActualDeparture time.Time `json:"actual_departure"`
	Status        string    `json:"status"` // e.g., "queued", "departed"
}