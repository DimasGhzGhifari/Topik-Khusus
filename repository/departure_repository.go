package repository

import (
	"context"
	"encoding/json"
	"redis-car-departure-system/domain"
	"strconv"
	"time"

	"github.com/redis/go-redis/v9"
)

type DepartureRepository interface {
	AddToQueue(ctx context.Context, vehicleID string) error
	ProcessDeparture(ctx context.Context) (*domain.Departure, error)
	GetQueueLength(ctx context.Context) (int64, error)
	SaveDepartureRecord(ctx context.Context, departure *domain.Departure) error
	GetDepartureHistory(ctx context.Context) ([]*domain.Departure, error)
}

type departureRepository struct {
	client *redis.Client
}

func NewDepartureRepository(client *redis.Client) DepartureRepository {
	return &departureRepository{client: client}
}

func (r *departureRepository) AddToQueue(ctx context.Context, vehicleID string) error {
	return r.client.LPush(ctx, "departure_queue", vehicleID).Err()
}

func (r *departureRepository) ProcessDeparture(ctx context.Context) (*domain.Departure, error) {
	vehicleID, err := r.client.RPop(ctx, "departure_queue").Result()
	if err != nil {
		return nil, err
	}

	departure := &domain.Departure{
		ID:              "dep_" + vehicleID + "_" + strconv.FormatInt(time.Now().Unix(), 10),
		VehicleID:       vehicleID,
		ActualDeparture: time.Now(),
		Status:          "departed",
	}

	err = r.SaveDepartureRecord(ctx, departure)
	return departure, err
}

func (r *departureRepository) GetQueueLength(ctx context.Context) (int64, error) {
	return r.client.LLen(ctx, "departure_queue").Result()
}

func (r *departureRepository) SaveDepartureRecord(ctx context.Context, departure *domain.Departure) error {
	data, err := json.Marshal(departure)
	if err != nil {
		return err
	}
	return r.client.Set(ctx, "departure:"+departure.ID, data, 0).Err()
}

func (r *departureRepository) GetDepartureHistory(ctx context.Context) ([]*domain.Departure, error) {
	keys, err := r.client.Keys(ctx, "departure:*").Result()
	if err != nil {
		return nil, err
	}
	var departures []*domain.Departure
	for _, key := range keys {
		data, err := r.client.Get(ctx, key).Result()
		if err != nil {
			continue
		}
		var departure domain.Departure
		json.Unmarshal([]byte(data), &departure)
		departures = append(departures, &departure)
	}
	return departures, nil
}