package repository

import (
	"context"
	"encoding/json"
	"redis-car-departure-system/domain"

	"github.com/redis/go-redis/v9"
)

type VehicleRepository interface {
	Save(ctx context.Context, vehicle *domain.Vehicle) error
	FindByID(ctx context.Context, id string) (*domain.Vehicle, error)
	FindAll(ctx context.Context) ([]*domain.Vehicle, error)
	UpdateStatus(ctx context.Context, id string, status string) error
	Delete(ctx context.Context, id string) error
}

type vehicleRepository struct {
	client *redis.Client
}

func NewVehicleRepository(client *redis.Client) VehicleRepository {
	return &vehicleRepository{client: client}
}

func (r *vehicleRepository) Save(ctx context.Context, vehicle *domain.Vehicle) error {
	data, err := json.Marshal(vehicle)
	if err != nil {
		return err
	}
	return r.client.Set(ctx, "vehicle:"+vehicle.ID, data, 0).Err()
}

func (r *vehicleRepository) FindByID(ctx context.Context, id string) (*domain.Vehicle, error) {
	data, err := r.client.Get(ctx, "vehicle:"+id).Result()
	if err != nil {
		return nil, err
	}
	var vehicle domain.Vehicle
	err = json.Unmarshal([]byte(data), &vehicle)
	return &vehicle, err
}

func (r *vehicleRepository) FindAll(ctx context.Context) ([]*domain.Vehicle, error) {
	keys, err := r.client.Keys(ctx, "vehicle:*").Result()
	if err != nil {
		return nil, err
	}
	var vehicles []*domain.Vehicle
	for _, key := range keys {
		data, err := r.client.Get(ctx, key).Result()
		if err != nil {
			continue
		}
		var vehicle domain.Vehicle
		json.Unmarshal([]byte(data), &vehicle)
		vehicles = append(vehicles, &vehicle)
	}
	return vehicles, nil
}

func (r *vehicleRepository) UpdateStatus(ctx context.Context, id string, status string) error {
	vehicle, err := r.FindByID(ctx, id)
	if err != nil {
		return err
	}
	vehicle.Status = status
	return r.Save(ctx, vehicle)
}

func (r *vehicleRepository) Delete(ctx context.Context, id string) error {
	return r.client.Del(ctx, "vehicle:"+id).Err()
}