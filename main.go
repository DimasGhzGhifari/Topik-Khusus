package main

import (
	"context"
	"fmt"
	"log"
	"redis-car-departure-system/domain"
	"redis-car-departure-system/repository"
	"redis-car-departure-system/usecase"
	"time"

	"github.com/redis/go-redis/v9"
)

func main() {
	// Connect to Redis
	rdb := redis.NewClient(&redis.Options{
		Addr: "localhost:6379",
	})
	defer rdb.Close()

	ctx := context.Background()

	// Ping Redis
	pong, err := rdb.Ping(ctx).Result()
	if err != nil {
		log.Fatal("Failed to connect to Redis:", err)
	}
	fmt.Println("Terhubung ke Redis:", pong)

	// Initialize repositories
	vehicleRepo := repository.NewVehicleRepository(rdb)
	departureRepo := repository.NewDepartureRepository(rdb)

	// Initialize usecases
	vehicleUC := usecase.NewVehicleUsecase(vehicleRepo, departureRepo)
	departureUC := usecase.NewDepartureUsecase(departureRepo, vehicleRepo)

	// Simulasi: Tambahkan beberapa mobil
	fmt.Println("\n=== MENAMBAHKAN MOBIL ===")
	vehicles := []*domain.Vehicle{
		{ID: "V001", DriverName: "John Doe", Destination: "Jakarta", DepartureTime: time.Now().Add(1 * time.Hour)},
		{ID: "V002", DriverName: "Jane Smith", Destination: "Bandung", DepartureTime: time.Now().Add(2 * time.Hour)},
		{ID: "V003", DriverName: "Bob Johnson", Destination: "Surabaya", DepartureTime: time.Now().Add(3 * time.Hour)},
	}

	for _, v := range vehicles {
		err := vehicleUC.CreateVehicle(ctx, v)
		if err != nil {
			log.Printf("Failed to create vehicle %s: %v", v.ID, err)
		} else {
			fmt.Printf("Mobil %s ditambahkan dengan status: %s\n", v.ID, v.Status)
		}
	}

	// Simulasi: Jadwalkan keberangkatan
	fmt.Println("\n=== MENJADWALKAN KEBERANGKATAN ===")
	for _, v := range vehicles {
		err := vehicleUC.ScheduleDeparture(ctx, v.ID, v.Destination)
		if err != nil {
			log.Printf("Failed to schedule departure for %s: %v", v.ID, err)
		} else {
			fmt.Printf("Mobil %s dijadwalkan untuk berangkat ke %s\n", v.ID, v.Destination)
		}
	}

	// Tampilkan panjang antrian
	queueLen, _ := departureUC.GetQueueLength(ctx)
	fmt.Printf("\nPanjang antrian keberangkatan: %d\n", queueLen)

	// Simulasi: Proses keberangkatan
	fmt.Println("\n=== MEMPROSES KEBERANGKATAN ===")
	for i := 0; i < len(vehicles); i++ {
		departure, err := departureUC.ProcessNextDeparture(ctx)
		if err != nil {
			log.Printf("Failed to process departure: %v", err)
			break
		}
		fmt.Printf("Mobil %s telah berangkat pada %s\n", departure.VehicleID, departure.ActualDeparture.Format("15:04:05"))
	}

	// Tampilkan sisa antrian
	queueLen, _ = departureUC.GetQueueLength(ctx)
	fmt.Printf("\nSisa antrian: %d\n", queueLen)

	// Tampilkan status semua mobil
	fmt.Println("\n=== STATUS SEMUA MOBIL ===")
	allVehicles, err := vehicleUC.GetAllVehicles(ctx)
	if err != nil {
		log.Printf("Failed to get vehicles: %v", err)
	} else {
		for _, v := range allVehicles {
			fmt.Printf("Mobil %s - Sopir: %s, Tujuan: %s, Status: %s\n", v.ID, v.DriverName, v.Destination, v.Status)
		}
	}

	// Tampilkan riwayat keberangkatan
	fmt.Println("\n=== RIWAYAT KEBERANGKATAN ===")
	history, err := departureUC.GetDepartureHistory(ctx)
	if err != nil {
		log.Printf("Failed to get departure history: %v", err)
	} else {
		for _, d := range history {
			fmt.Printf("ID: %s, Mobil: %s, Waktu: %s\n", d.ID, d.VehicleID, d.ActualDeparture.Format("15:04:05"))
		}
	}
}